<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\GoogleDriveSyncService;
use App\Services\GoogleOAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request, GoogleOAuthService $googleOAuthService): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Handle profile photo removal
        if ($request->has('remove_photo')) {
            if ($user->profile_photo) {
                // Delete from local storage
                Storage::disk('public')->delete($user->profile_photo);
                
                // Delete from admin Google Drive if exists and connected
                if ($user->google_drive_file_id && $googleOAuthService->isConnected()) {
                    $googleOAuthService->deleteFile($user, $user->google_drive_file_id);
                }
                
                $user->profile_photo = null;
                $user->google_drive_file_id = null;
            }
        }
        // Handle profile photo upload
        elseif ($request->hasFile('profile_photo')) {
            // Delete old profile photo if exists
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
                
                // Delete from admin Google Drive
                if ($user->google_drive_file_id && $googleOAuthService->isConnected()) {
                    $googleOAuthService->deleteFile($user, $user->google_drive_file_id);
                }
            }

            // Store new profile photo locally
            $photoPath = $request->file('profile_photo')->store('uploads/profile', 'public');
            $validated['profile_photo'] = $photoPath;
            
            // Sync to admin Google Drive if connected
            if ($googleOAuthService->isConnected()) {
                $fileName = basename($photoPath);
                $googleDriveResult = $googleOAuthService->uploadFile($user, $photoPath, $fileName);
                
                if ($googleDriveResult) {
                    $user->google_drive_file_id = $googleDriveResult['file_id'];
                    \Log::info('Profile photo synced to ADMIN Google Drive', [
                        'user_id' => $user->id,
                        'username' => $user->username,
                        'file_id' => $googleDriveResult['file_id'],
                        'web_link' => $googleDriveResult['web_link'],
                        'admin_drive' => 'nathanjethoe007@gmail.com',
                    ]);
                }
            }
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
