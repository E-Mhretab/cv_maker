<?php

namespace App\Services;

use App\Models\Cv;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class GuestCvTransferService
{
    /**
     * Transfer guest CVs to a user account based on email matching
     */
    public function transferGuestCvsToUser(User $user): int
    {
        $transferredCount = 0;
        
        try {
            // Find guest CVs that match the user's email
            $guestCvs = Cv::whereNull('user_id')
                ->where('email', $user->email)
                ->get();
            
            if ($guestCvs->count() > 0) {
                Log::info('Found guest CVs to transfer', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'guest_cv_count' => $guestCvs->count()
                ]);
                
                // Transfer each guest CV to the user
                foreach ($guestCvs as $cv) {
                    $cv->update(['user_id' => $user->id]);
                    $transferredCount++;
                    
                    Log::info('Transferred guest CV to user', [
                        'cv_id' => $cv->id,
                        'cv_name' => $cv->name,
                        'user_id' => $user->id
                    ]);
                }
            }
            
        } catch (\Exception $e) {
            Log::error('Error transferring guest CVs', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
        
        return $transferredCount;
    }
    
    /**
     * Check if a user has any guest CVs that can be transferred
     */
    public function hasTransferableGuestCvs(string $email): bool
    {
        return Cv::whereNull('user_id')
            ->where('email', $email)
            ->exists();
    }
    
    /**
     * Get count of transferable guest CVs for an email
     */
    public function getTransferableGuestCvsCount(string $email): int
    {
        return Cv::whereNull('user_id')
            ->where('email', $email)
            ->count();
    }
}
