<?php

namespace App\Http\Controllers;

use App\Services\GoogleOAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GoogleDriveController extends Controller
{
    protected GoogleOAuthService $googleOAuthService;

    public function __construct(GoogleOAuthService $googleOAuthService)
    {
        $this->googleOAuthService = $googleOAuthService;
    }

    /**
     * Redirect ADMIN to Google OAuth consent screen
     */
    public function connect(): RedirectResponse
    {
        $user = Auth::user();

        // Only admin can connect Google Drive
        if ($user->role !== 'admin') {
            return redirect()->route('profile.edit')
                ->with('error', 'Only administrators can connect Google Drive. Contact your system administrator.');
        }

        try {
            $authUrl = $this->googleOAuthService->getAuthUrl();
            
            Log::info('Admin initiating Google Drive connection', [
                'user_id' => $user->id,
                'username' => $user->username
            ]);

            return redirect()->away($authUrl);

        } catch (\Exception $e) {
            Log::error('Failed to generate Google auth URL', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);

            return redirect()->route('profile.edit')
                ->with('error', 'Failed to connect to Google Drive. Please try again.');
        }
    }

    /**
     * Handle OAuth callback from Google (ADMIN AUTHORIZATION)
     */
    public function callback(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Check for errors
        if ($request->has('error')) {
            Log::warning('OAuth callback received error', [
                'error' => $request->get('error'),
                'user_id' => $user->id
            ]);

            return redirect()->route('profile.edit')
                ->with('error', 'Google Drive connection was cancelled or failed.');
        }

        // Check for authorization code
        if (!$request->has('code')) {
            return redirect()->route('profile.edit')
                ->with('error', 'No authorization code received from Google.');
        }

        $code = $request->get('code');

        // Exchange code for ADMIN tokens (stored centrally)
        if ($this->googleOAuthService->handleCallback($code)) {
            Log::info('Admin Google Drive connected successfully', [
                'connected_by_user_id' => $user->id,
                'admin_email' => 'nathanjethoe007@gmail.com'
            ]);

            return redirect()->route('profile.edit')
                ->with('status', 'google-drive-connected')
                ->with('success', 'Google Drive connected successfully! All users\' profile photos will now be backed up to the admin Google Drive (nathanjethoe007@gmail.com).');
        }

        return redirect()->route('profile.edit')
            ->with('error', 'Failed to connect to Google Drive. Please try again.');
    }

    /**
     * Disconnect admin Google Drive (ADMIN ONLY - affects all users)
     */
    public function disconnect(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Check if user is admin
        if ($user->role !== 'admin') {
            return redirect()->route('profile.edit')
                ->with('error', 'Only administrators can disconnect Google Drive.');
        }

        if ($this->googleOAuthService->revokeAccess()) {
            Log::info('Admin Google Drive disconnected', ['by_user_id' => $user->id]);

            return redirect()->route('profile.edit')
                ->with('status', 'google-drive-disconnected')
                ->with('success', 'Google Drive disconnected. All users\' future profile photos will only be stored locally.');
        }

        return redirect()->route('profile.edit')
            ->with('error', 'Failed to disconnect Google Drive. Please try again.');
    }

    /**
     * Manually sync existing profile photo to admin Google Drive
     */
    public function syncPhoto(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (!$user->profile_photo) {
            return redirect()->route('profile.edit')
                ->with('error', 'No profile photo to sync.');
        }

        if (!$this->googleOAuthService->isConnected()) {
            return redirect()->route('profile.edit')
                ->with('error', 'Google Drive is not connected. Please contact administrator.');
        }

        try {
            $fileName = basename($user->profile_photo);
            $result = $this->googleOAuthService->uploadFile($user, $user->profile_photo, $fileName);

            if ($result) {
                $user->google_drive_file_id = $result['file_id'];
                $user->save();

                return redirect()->route('profile.edit')
                    ->with('status', 'photo-synced')
                    ->with('success', 'Profile photo synced to admin Google Drive successfully!');
            }

            return redirect()->route('profile.edit')
                ->with('error', 'Failed to sync photo to Google Drive.');

        } catch (\Exception $e) {
            Log::error('Manual photo sync failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);

            return redirect()->route('profile.edit')
                ->with('error', 'An error occurred while syncing to Google Drive.');
        }
    }

    /**
     * Sync all users' profile photos to admin Google Drive (ADMIN ONLY)
     */
    public function syncAllPhotos(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Only admin can sync all photos
        if ($user->role !== 'admin') {
            return redirect()->route('profile.edit')
                ->with('error', 'Only administrators can sync all photos.');
        }

        if (!$this->googleOAuthService->isConnected()) {
            return redirect()->route('profile.edit')
                ->with('error', 'Google Drive is not connected. Please connect first.');
        }

        try {
            // Get all users with profile photos
            $users = \App\Models\User::whereNotNull('profile_photo')->get();
            
            $successCount = 0;
            $failCount = 0;
            $skippedCount = 0;

            foreach ($users as $targetUser) {
                // Skip if already synced
                if ($targetUser->hasGoogleDrivePhoto()) {
                    $skippedCount++;
                    continue;
                }

                $fileName = basename($targetUser->profile_photo);
                $result = $this->googleOAuthService->uploadFile($targetUser, $targetUser->profile_photo, $fileName);

                if ($result) {
                    $targetUser->google_drive_file_id = $result['file_id'];
                    $targetUser->save();
                    $successCount++;
                } else {
                    $failCount++;
                }
            }

            Log::info('Bulk photo sync completed', [
                'success' => $successCount,
                'failed' => $failCount,
                'skipped' => $skippedCount,
                'by_admin' => $user->id
            ]);

            $message = "Sync completed: {$successCount} uploaded, {$skippedCount} already synced";
            if ($failCount > 0) {
                $message .= ", {$failCount} failed";
            }

            return redirect()->route('profile.edit')
                ->with('status', 'photos-synced')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Bulk photo sync failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);

            return redirect()->route('profile.edit')
                ->with('error', 'An error occurred while syncing photos to Google Drive.');
        }
    }
}


