<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GoogleDriveSyncService
{
    /**
     * Upload a file to Google Drive and return the file ID and shareable link
     *
     * @param string $localPath Local path relative to storage/app/public
     * @param string $fileName File name to use on Google Drive
     * @return array|null Returns ['file_id' => string, 'web_link' => string, 'edit_link' => string] or null on failure
     */
    public function uploadToGoogleDrive(string $localPath, string $fileName): ?array
    {
        try {
            // Check if Google Drive is configured
            if (!$this->isGoogleDriveConfigured()) {
                Log::warning('Google Drive is not configured. File will only be stored locally.');
                return null;
            }

            Log::info('Starting Google Drive upload', ['fileName' => $fileName, 'localPath' => $localPath]);

            // Read file from local storage
            $fileContents = Storage::disk('public')->get($localPath);
            
            if (!$fileContents) {
                Log::error("Could not read local file: {$localPath}");
                return null;
            }

            Log::info('File read from local storage', ['size' => strlen($fileContents)]);

            // Upload to Google Drive using write method
            $disk = Storage::disk('google');
            
            try {
                $disk->write($fileName, $fileContents);
                Log::info('File written to Google Drive', ['fileName' => $fileName]);
            } catch (\Exception $writeException) {
                Log::error('Google Drive write failed', [
                    'error' => $writeException->getMessage(),
                    'fileName' => $fileName,
                    'trace' => $writeException->getTraceAsString()
                ]);
                return null;
            }

            // Get file metadata to retrieve file ID
            try {
                $adapter = $disk->getAdapter();
                $metadata = $adapter->getMetadata($fileName);
                
                if (!$metadata || !isset($metadata['path'])) {
                    Log::warning("Could not retrieve Google Drive file metadata, using filename as fallback", ['fileName' => $fileName]);
                    // Use a fallback - extract from file list
                    $fileId = $this->getFileIdByName($fileName);
                    
                    if (!$fileId) {
                        Log::error("Could not find file ID for: {$fileName}");
                        return null;
                    }
                } else {
                    $fileId = $metadata['path'];
                }
            } catch (\Exception $metadataException) {
                Log::warning('Metadata retrieval failed, attempting fallback', [
                    'error' => $metadataException->getMessage()
                ]);
                $fileId = $this->getFileIdByName($fileName);
                
                if (!$fileId) {
                    return null;
                }
            }

            // Generate shareable links
            $webLink = "https://drive.google.com/file/d/{$fileId}/view";
            $editLink = "https://drive.google.com/file/d/{$fileId}/edit";

            Log::info("File uploaded to Google Drive successfully", [
                'file_name' => $fileName,
                'file_id' => $fileId,
            ]);

            return [
                'file_id' => $fileId,
                'web_link' => $webLink,
                'edit_link' => $editLink,
            ];

        } catch (\Exception $e) {
            Log::error('Google Drive upload failed: ' . $e->getMessage(), [
                'file' => $fileName,
                'class' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Get file ID by name from Google Drive
     */
    private function getFileIdByName(string $fileName): ?string
    {
        try {
            $disk = Storage::disk('google');
            $files = $disk->listContents('/', false);
            
            foreach ($files as $file) {
                if ($file['name'] === $fileName || $file['basename'] === $fileName) {
                    return $file['path'] ?? $file['file_id'] ?? null;
                }
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Failed to get file ID by name: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete a file from Google Drive
     *
     * @param string $fileName File name on Google Drive
     * @return bool
     */
    public function deleteFromGoogleDrive(string $fileName): bool
    {
        try {
            if (!$this->isGoogleDriveConfigured()) {
                return true; // Silently succeed if not configured
            }

            if (Storage::disk('google')->exists($fileName)) {
                Storage::disk('google')->delete($fileName);
                Log::info("File deleted from Google Drive: {$fileName}");
                return true;
            }

            return true; // File doesn't exist, consider it a success
        } catch (\Exception $e) {
            Log::error('Google Drive delete failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if Google Drive is properly configured
     *
     * @return bool
     */
    public function isGoogleDriveConfigured(): bool
    {
        $serviceAccountFile = storage_path('app/google/' . env('GOOGLE_DRIVE_SERVICE_ACCOUNT_FILE', 'service-account.json'));
        
        return (
            !empty(env('GOOGLE_DRIVE_CLIENT_ID')) ||
            file_exists($serviceAccountFile)
        ) && !empty(env('GOOGLE_DRIVE_FOLDER_ID'));
    }

    /**
     * Get the web view URL for a Google Drive file
     *
     * @param string $fileId Google Drive file ID
     * @return string
     */
    public function getWebViewUrl(string $fileId): string
    {
        return "https://drive.google.com/file/d/{$fileId}/view";
    }

    /**
     * Get the edit URL for a Google Drive file
     *
     * @param string $fileId Google Drive file ID
     * @return string
     */
    public function getEditUrl(string $fileId): string
    {
        return "https://drive.google.com/file/d/{$fileId}/edit";
    }

    /**
     * Get the download URL for a Google Drive file
     *
     * @param string $fileId Google Drive file ID
     * @return string
     */
    public function getDownloadUrl(string $fileId): string
    {
        return "https://drive.google.com/uc?export=download&id={$fileId}";
    }

    /**
     * Sync an existing local file to Google Drive
     *
     * @param string $localPath Local path relative to storage/app/public
     * @return array|null
     */
    public function syncExistingFile(string $localPath): ?array
    {
        $fileName = basename($localPath);
        return $this->uploadToGoogleDrive($localPath, $fileName);
    }
}

