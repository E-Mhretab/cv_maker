# Laravel CV Maker - Live Server Deployment Guide

## Database Configuration

Based on your original PHP project, here are the database credentials:

### Database Settings
```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=luxdemoestate_cv
DB_USERNAME=luxdemoestate_CV
DB_PASSWORD=!]5=Y75m}+MCuSU7
```

## Complete .env File for Live Server

Create a `.env` file in your Laravel project root with the following content:

```env
APP_NAME="CV Maker"
APP_ENV=production
APP_KEY=base64:your-app-key-here
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://your-domain.com

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
APP_MAINTENANCE_STORE=database

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=luxdemoestate_cv
DB_USERNAME=luxdemoestate_CV
DB_PASSWORD=!]5=Y75m}+MCuSU7

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync

CACHE_STORE=file
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```

## Deployment Steps

### 1. Upload Files
Upload all Laravel project files to your live server.

### 2. Set Permissions
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### 3. Install Dependencies
```bash
composer install --optimize-autoloader --no-dev
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Run Migrations
```bash
php artisan migrate --force
```

### 6. Clear Caches
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 7. Set Document Root
Point your web server's document root to the `public` directory.

## Important Notes

### Database
- ✅ **Database exists** - `luxdemoestate_cv` database is already set up
- ✅ **Tables exist** - All tables from your original project are available
- ✅ **Data preserved** - All existing CV data will be accessible

### PDF Export
- ✅ **wkhtmltopdf available** - PDF generation will work automatically
- ✅ **Professional PDFs** - High-quality PDF export
- ✅ **No HTML fallback** - Direct PDF generation on live server

### Security
- ✅ **Production mode** - `APP_DEBUG=false`
- ✅ **Optimized** - Cached routes, views, and config
- ✅ **Secure** - Proper file permissions

## Verification

After deployment, verify:
1. ✅ **Homepage loads** - `/` shows the welcome page
2. ✅ **Login works** - Admin and user login
3. ✅ **CV creation** - Guest and authenticated CV creation
4. ✅ **PDF export** - PDF generation works
5. ✅ **XML export** - XML export works
6. ✅ **Admin panel** - Admin dashboard accessible

## Troubleshooting

### If PDF export doesn't work:
- Check if `wkhtmltopdf` is installed on the server
- Verify exec functions are enabled
- Check server logs for errors

### If database connection fails:
- Verify database credentials
- Check if database server is running
- Ensure user has proper permissions

### If routes don't work:
- Run `php artisan route:cache`
- Check `.htaccess` file in public directory
- Verify web server configuration
