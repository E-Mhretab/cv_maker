<?php

namespace App\Console\Commands;

use App\Mail\UserInactivityMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CheckUserInactivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:check-inactivity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for users who have been inactive for over 1 hour and send notification emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting user inactivity check...');
        
        try {
            // Get users who haven't logged in for over 1 hour
            $inactiveUsers = User::where('last_login', '<', now()->subHour())
                ->where('last_login', '>', now()->subHours(2)) // Only users inactive between 1-2 hours
                ->whereNotNull('email')
                ->get();

            $this->info("Found {$inactiveUsers->count()} inactive users");

            $emailCount = 0;

            foreach ($inactiveUsers as $user) {
                try {
                    // Send inactivity email
                    Mail::mailer('smtp')->to($user->email)->send(new UserInactivityMail($user));
                    
                    $emailCount++;
                    $this->line("Sent inactivity email to: {$user->email}");
                    
                    // Log the activity
                    Log::info('Inactivity email sent', [
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'last_login' => $user->last_login,
                        'inactive_duration' => now()->diffInMinutes($user->last_login) . ' minutes'
                    ]);
                    
                } catch (\Exception $e) {
                    $this->error("Failed to send email to {$user->email}: {$e->getMessage()}");
                    Log::error('Failed to send inactivity email', [
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            $this->info("Successfully sent {$emailCount} inactivity emails");
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("Command failed: {$e->getMessage()}");
            Log::error('User inactivity check failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return Command::FAILURE;
        }
    }
}
