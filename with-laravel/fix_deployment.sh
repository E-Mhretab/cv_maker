#!/bin/bash

echo "🔧 Fixing Laravel Deployment"
echo "=========================="

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: Please run this script from your Laravel project root directory"
    exit 1
fi

echo "📦 Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev

echo "🔑 Generating application key..."
php artisan key:generate

echo "🗄️ Running database migrations..."
php artisan migrate --force

echo "📁 Setting file permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

echo "⚡ Caching for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🌐 Creating .htaccess redirect..."
cat > .htaccess << 'EOF'
RewriteEngine On
RewriteCond %{REQUEST_URI} !^/public/
RewriteRule ^(.*)$ /public/$1 [L,R=301]
EOF

echo "✅ Fix complete!"
echo ""
echo "📋 Next steps:"
echo "1. Update your web server document root to point to the 'public' directory"
echo "2. Or access your site via: https://your-domain.com/path/to/project/public/"
echo "3. Test the application"
echo ""
echo "🔧 Database configuration for .env file:"
echo "DB_CONNECTION=mysql"
echo "DB_HOST=localhost"
echo "DB_PORT=3306"
echo "DB_DATABASE=luxdemoestate_cv"
echo "DB_USERNAME=luxdemoestate_CV"
echo "DB_PASSWORD=!]5=Y75m}+MCuSU7"
echo ""
echo "🌐 Update APP_URL in .env to: https://luxdemoestate.com/Esey_laravel/cv_maker-Esey/with-laravel/public"
