# Multi-Site Laravel Setup Guide

## Your Current Setup:
- **Domain:** https://luxdemoestate.com/
- **Esey Laravel:** https://luxdemoestate.com/Esey_laravel/cv_maker-Esey/with-laravel/
- **Other Projects:** Mirian_laravel, Nathan_laravel, etc.

## Fix for Esey Laravel Project:

### 1. Create .env File in Esey Laravel Root:
```env
APP_NAME="CV Maker - Esey"
APP_ENV=production
APP_KEY=base64:your-app-key-here
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://luxdemoestate.com/Esey_laravel/cv_maker-Esey/with-laravel/public

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=luxdemoestate_cv
DB_USERNAME=luxdemoestate_CV
DB_PASSWORD=!]5=Y75m}+MCuSU7

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false

CACHE_STORE=file
QUEUE_CONNECTION=sync

MAIL_MAILER=log
MAIL_FROM_ADDRESS="hello@luxdemoestate.com"
MAIL_FROM_NAME="CV Maker - Esey"
```

### 2. Run Setup Commands:
```bash
cd /path/to/Esey_laravel/cv_maker-Esey/with-laravel/

# Install dependencies
composer install --optimize-autoloader --no-dev

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Set permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Access URLs:
- **Homepage:** https://luxdemoestate.com/Esey_laravel/cv_maker-Esey/with-laravel/public/
- **Admin:** https://luxdemoestate.com/Esey_laravel/cv_maker-Esey/with-laravel/public/admin/dashboard
- **CV Management:** https://luxdemoestate.com/Esey_laravel/cv_maker-Esey/with-laravel/public/cvs

## Multi-Site Benefits:
✅ **Isolated Projects** - Each Laravel project independent
✅ **Shared Database** - All projects use same database
✅ **Easy Management** - Users choose which project to access
✅ **Scalable** - Add more projects easily

## Database Sharing:
All your Laravel projects can share the same database:
- **Database:** luxdemoestate_cv
- **Users:** Shared across all projects
- **CVs:** Accessible from any project
- **Admin:** Can manage all projects

## Project Structure:
```
luxdemoestate.com/
├── Esey_laravel/
│   └── cv_maker-Esey/
│       └── with-laravel/
│           ├── public/          ← Point here
│           ├── app/
│           ├── config/
│           └── .env
├── Mirian_laravel/
├── Nathan_laravel/
└── re-chatbot/
```
