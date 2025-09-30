# .env Configuration for LuxDemo Estate

## Email Configuration

Add these lines to your `.env` file:

```env
# Email Configuration for LuxDemo Estate
MAIL_MAILER=smtp
MAIL_HOST=mail.luxdemoestate.com
MAIL_PORT=465
MAIL_USERNAME=web@luxdemoestate.com
MAIL_PASSWORD="ZPi4Z}rZ704Az&b@"
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=web@luxdemoestate.com
MAIL_FROM_NAME="LuxDemo Estate"

# SendGrid API Key (for bulk sending)
SENDGRID_API_KEY=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
```

## Complete .env File Template

If you need to create a new .env file, use this template:

```env
APP_NAME=LuxDemo Estate CV Maker
APP_ENV=local
APP_KEY=base64:your-app-key-here
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost:8000

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
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cv_maker
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Email Configuration for LuxDemo Estate
MAIL_MAILER=smtp
MAIL_HOST=mail.luxdemoestate.com
MAIL_PORT=465
MAIL_USERNAME=web@luxdemoestate.com
MAIL_PASSWORD="ZPi4Z}rZ704Az&b@"
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=web@luxdemoestate.com
MAIL_FROM_NAME="LuxDemo Estate"

# SendGrid API Key (for bulk sending)
SENDGRID_API_KEY=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

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```

## Steps to Update .env

1. **Open your `.env` file** in the project root
2. **Find the MAIL_* lines** and replace them with the new configuration
3. **Save the file**
4. **Restart your Laravel application**

## Verification

After updating .env, test with:
```bash
php artisan config:clear
php artisan config:cache
```

## Benefits

✅ **Professional email** - web@luxdemoestate.com
✅ **Better deliverability** - Your own domain
✅ **SSL encryption** - Secure transmission
✅ **No spam issues** - Professional sender
