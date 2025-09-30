# CRON System Setup Guide

## User Inactivity CRON System

This system automatically checks for users who haven't logged in for over 1 hour and sends them a reminder email.

### 🚀 Features

- **Automatic Detection**: Checks users inactive for 1-2 hours
- **Professional Emails**: Beautiful HTML email templates
- **Smart Scheduling**: Runs every 30 minutes
- **Overlap Protection**: Prevents multiple instances
- **Background Processing**: Non-blocking execution
- **Comprehensive Logging**: Full activity tracking

### 📧 Email System

**Email Template**: `resources/views/emails/user-inactivity.blade.php`
- Professional design with LuxDemo Estate branding
- Personalized content with user's last login time
- Call-to-action buttons (View CVs, Create New CV)
- Unsubscribe option
- Mobile-responsive design

**Mailable Class**: `app/Mail/UserInactivityMail.php`
- Subject: "We Miss You! - LuxDemo Estate CV Maker"
- From: web@luxdemoestate.com
- Professional metadata and tags

### ⏰ Scheduling

**Laravel Scheduler**: `routes/console.php`
```php
Schedule::command('users:check-inactivity')
    ->everyThirtyMinutes()
    ->withoutOverlapping()
    ->runInBackground();
```

**Command**: `users:check-inactivity`
- Checks users with `last_login` between 1-2 hours ago
- Sends personalized inactivity emails
- Logs all activities

### 🔧 Server Setup

**1. Add to Crontab:**
```bash
# Edit crontab
crontab -e

# Add this line (runs every minute, Laravel handles 30-minute intervals)
* * * * * cd /Applications/XAMPP/xamppfiles/htdocs/cv_maker/with-laravel && php artisan schedule:run >> /dev/null 2>&1
```

**2. Test the System:**
```bash
# Manual test
php artisan users:check-inactivity

# Test scheduler
php artisan schedule:run

# List scheduled tasks
php artisan schedule:list
```

### 📊 Monitoring

**Logs**: `storage/logs/laravel.log`
- Successful email sends
- Failed email attempts
- Command execution details
- Error tracking

**Command Output:**
- Number of inactive users found
- Emails sent successfully
- Error messages for failures

### 🎯 How It Works

1. **Every 30 minutes**, Laravel scheduler runs the command
2. **Query users** with `last_login` between 1-2 hours ago
3. **Send personalized emails** to inactive users
4. **Log all activities** for monitoring
5. **Prevent duplicates** with overlap protection

### 📧 Email Content

**Subject**: "We Miss You! - LuxDemo Estate CV Maker"
**Content**:
- Personalized greeting with user's name
- Last login time and inactive duration
- Professional inactivity reminder
- Call-to-action buttons
- LuxDemo Estate branding
- Unsubscribe option

### ✅ Testing

**Manual Test:**
```bash
php artisan users:check-inactivity
```

**Expected Output:**
```
Starting user inactivity check...
Found X inactive users
Sent inactivity email to: user@example.com
Successfully sent X inactivity emails
```

**Email Verification:**
- Check user inboxes for inactivity emails
- Verify email content and design
- Test unsubscribe functionality

### 🔧 Troubleshooting

**If emails not sending:**
1. Check SMTP configuration in `.env`
2. Verify LuxDemo Estate SMTP settings
3. Check Laravel logs for errors
4. Test email sending manually

**If CRON not running:**
1. Verify crontab entry is correct
2. Check file permissions
3. Test `php artisan schedule:run` manually
4. Check server timezone settings

**If no inactive users found:**
1. Check `last_login` column in users table
2. Verify user data exists
3. Adjust time ranges if needed
4. Test with specific user data

### 📈 Benefits

- **User Engagement**: Keeps users active on platform
- **Professional Communication**: Branded email templates
- **Automated Process**: No manual intervention needed
- **Scalable**: Handles any number of users
- **Reliable**: Overlap protection and error handling
- **Trackable**: Comprehensive logging system

### 🎉 Ready to Use!

The CRON system is now fully configured and ready to automatically send inactivity reminder emails to users who haven't logged in for over 1 hour!
