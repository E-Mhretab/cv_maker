<?php

namespace App\Services;

use App\Models\User;
use App\Models\AdminGoogleToken;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GoogleOAuthService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(config('services.google.redirect_uri'));
        $this->client->addScope(Drive::DRIVE_FILE);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
    }

    /**
     * Get the OAuth authorization URL
     */
    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    /**
     * Handle OAuth callback and exchange code for tokens (ADMIN ONLY)
     */
    public function handleCallback(string $code): bool
    {
        try {
            $token = $this->client->fetchAccessTokenWithAuthCode($code);

            if (isset($token['error'])) {
                Log::error('OAuth token exchange failed', ['error' => $token['error']]);
                return false;
            }

            // Store tokens in admin_google_tokens table (singleton)
            $adminToken = AdminGoogleToken::firstOrCreate(['key' => 'default']);
            
            $adminToken->access_token = Crypt::encryptString($token['access_token']);
            
            if (isset($token['refresh_token'])) {
                $adminToken->refresh_token = Crypt::encryptString($token['refresh_token']);
            }
            
            if (isset($token['expires_in'])) {
                $adminToken->expires_at = now()->addSeconds($token['expires_in']);
            }

            $adminToken->save();

            Log::info('Admin Google OAuth tokens stored successfully');

            return true;

        } catch (\Exception $e) {
            Log::error('OAuth callback handling failed', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Refresh the admin access token using refresh token
     */
    public function refreshAccessToken(): bool
    {
        try {
            $adminToken = AdminGoogleToken::getToken();
            
            if (!$adminToken || !$adminToken->refresh_token) {
                return false;
            }

            $refreshToken = Crypt::decryptString($adminToken->refresh_token);
            $this->client->refreshToken($refreshToken);
            $token = $this->client->getAccessToken();

            if (isset($token['access_token'])) {
                $adminToken->access_token = Crypt::encryptString($token['access_token']);
                
                if (isset($token['expires_in'])) {
                    $adminToken->expires_at = now()->addSeconds($token['expires_in']);
                }
                
                $adminToken->save();

                Log::info('Admin Google access token refreshed');
                return true;
            }

            return false;

        } catch (\Exception $e) {
            Log::error('Admin token refresh failed', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get a valid admin access token
     */
    protected function getValidAccessToken(): ?string
    {
        $adminToken = AdminGoogleToken::getToken();
        
        if (!$adminToken || !$adminToken->refresh_token) {
            return null;
        }

        // Check if token needs refresh
        if (!$adminToken->expires_at || $adminToken->expires_at->isPast()) {
            if (!$this->refreshAccessToken()) {
                return null;
            }
            $adminToken->refresh();
        }

        return $adminToken->access_token ? Crypt::decryptString($adminToken->access_token) : null;
    }

    /**
     * Upload a file to admin's Google Drive (for any user's profile photo)
     */
    public function uploadFile(User $user, string $localPath, string $fileName): ?array
    {
        try {
            $accessToken = $this->getValidAccessToken();
            
            if (!$accessToken) {
                Log::warning('No valid admin access token available');
                return null;
            }

            $this->client->setAccessToken($accessToken);
            $driveService = new Drive($this->client);

            // Read local file
            $fileContents = Storage::disk('public')->get($localPath);
            
            if (!$fileContents) {
                Log::error('Could not read local file', ['path' => $localPath]);
                return null;
            }

            // Use Nathan's existing laravel_uploads folder
            $folderId = '1Js5d8gjLylWTA6AsxstCjbEaleq19TkX';

            // Add user identifier to filename for organization
            $userFileName = $user->id . '_' . $user->username . '_' . $fileName;

            // Create file metadata
            $fileMetadata = new DriveFile([
                'name' => $userFileName,
                'parents' => [$folderId],
                'description' => "Profile photo for user: {$user->username} (ID: {$user->id})",
            ]);

            // Upload file
            $file = $driveService->files->create($fileMetadata, [
                'data' => $fileContents,
                'mimeType' => Storage::disk('public')->mimeType($localPath),
                'uploadType' => 'multipart',
                'fields' => 'id,name,webViewLink,webContentLink'
            ]);

            Log::info('File uploaded to ADMIN Google Drive', [
                'user_id' => $user->id,
                'username' => $user->username,
                'file_id' => $file->id,
                'file_name' => $userFileName
            ]);

            return [
                'file_id' => $file->id,
                'web_link' => $file->webViewLink,
                'download_link' => $file->webContentLink ?? "https://drive.google.com/uc?export=download&id={$file->id}",
            ];

        } catch (\Exception $e) {
            Log::error('Google Drive upload failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'file' => $fileName
            ]);
            return null;
        }
    }

    /**
     * Delete a file from admin's Google Drive
     */
    public function deleteFile(User $user, string $fileId): bool
    {
        try {
            $accessToken = $this->getValidAccessToken();
            
            if (!$accessToken) {
                return false;
            }

            $this->client->setAccessToken($accessToken);
            $driveService = new Drive($this->client);

            $driveService->files->delete($fileId);

            Log::info('File deleted from ADMIN Google Drive', [
                'user_id' => $user->id,
                'file_id' => $fileId
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Google Drive delete failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'file_id' => $fileId
            ]);
            return false;
        }
    }

    /**
     * Find or create a folder in Google Drive
     */
    protected function findOrCreateFolder(Drive $driveService, string $folderName): string
    {
        try {
            // Search for existing folder
            $response = $driveService->files->listFiles([
                'q' => "name='{$folderName}' and mimeType='application/vnd.google-apps.folder' and trashed=false",
                'spaces' => 'drive',
                'fields' => 'files(id, name)',
            ]);

            if (count($response->files) > 0) {
                return $response->files[0]->id;
            }

            // Create new folder
            $folderMetadata = new DriveFile([
                'name' => $folderName,
                'mimeType' => 'application/vnd.google-apps.folder',
            ]);

            $folder = $driveService->files->create($folderMetadata, [
                'fields' => 'id'
            ]);

            Log::info('Created Google Drive folder', [
                'folder_name' => $folderName,
                'folder_id' => $folder->id
            ]);

            return $folder->id;

        } catch (\Exception $e) {
            Log::error('Failed to find/create folder', [
                'error' => $e->getMessage(),
                'folder_name' => $folderName
            ]);
            throw $e;
        }
    }

    /**
     * Revoke admin Google Drive access (ADMIN ONLY)
     */
    public function revokeAccess(): bool
    {
        try {
            $adminToken = AdminGoogleToken::getToken();
            
            if ($adminToken && $adminToken->access_token) {
                $accessToken = Crypt::decryptString($adminToken->access_token);
                $this->client->revokeToken($accessToken);
            }

            // Delete admin token record
            if ($adminToken) {
                $adminToken->delete();
            }

            Log::info('Admin Google Drive access revoked');

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to revoke admin Google access', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Check if admin Google Drive is connected
     */
    public function isConnected(): bool
    {
        return AdminGoogleToken::isConnected();
    }

    /**
     * Get admin token model
     */
    public function getAdminToken(): ?AdminGoogleToken
    {
        return AdminGoogleToken::getToken();
    }
}

