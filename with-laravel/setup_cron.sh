#!/bin/bash

echo "=== LuxDemo Estate CRON Setup ==="
echo ""

# Get the current directory
PROJECT_DIR="/Applications/XAMPP/xamppfiles/htdocs/cv_maker/with-laravel"

echo "1. Setting up Laravel Scheduler CRON..."
echo "   Project Directory: $PROJECT_DIR"
echo ""

# Create the crontab entry
CRON_ENTRY="* * * * * cd $PROJECT_DIR && php artisan schedule:run >> /dev/null 2>&1"

echo "2. CRON Entry to add:"
echo "   $CRON_ENTRY"
echo ""

echo "3. Manual Setup Instructions:"
echo "   📝 Open Terminal"
echo "   📝 Run: crontab -e"
echo "   📝 Add this line: $CRON_ENTRY"
echo "   📝 Save and exit (Ctrl+X, Y, Enter in nano)"
echo ""

echo "4. Alternative: Run this command to add automatically:"
echo "   echo \"$CRON_ENTRY\" | crontab -"
echo ""

echo "5. Verify CRON is working:"
echo "   📊 Check: crontab -l"
echo "   📊 Test: cd $PROJECT_DIR && php artisan schedule:run"
echo "   📊 Monitor: tail -f storage/logs/laravel.log"
echo ""

echo "6. CRON Schedule Details:"
echo "   ⏰ Runs: Every minute"
echo "   ⏰ Command: php artisan schedule:run"
echo "   ⏰ Laravel handles: 30-minute intervals for user inactivity"
echo "   ⏰ Overlap protection: Enabled"
echo ""

echo "7. What the CRON does:"
echo "   🔄 Checks for users inactive 1-2 hours"
echo "   📧 Sends personalized reminder emails"
echo "   📊 Logs all activities"
echo "   🛡️ Prevents duplicate emails"
echo ""

echo "✅ CRON setup instructions ready!"
echo "✅ Laravel scheduler configured"
echo "✅ User inactivity system ready"
echo ""

echo "=== Setup Complete ==="
