#!/bin/bash

# CV Maker - Live Deployment Script
# Run this script on your production server

echo "🚀 Starting CV Maker deployment..."

# Check if .env file exists
if [ ! -f .env ]; then
    echo "❌ .env file not found! Please create it first."
    echo "Copy .env.example to .env and configure your database settings."
    exit 1
fi

# Install/Update Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev

# Generate application key if not set
echo "🔑 Generating application key..."
php artisan key:generate

# Clear all caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set correct permissions
echo "🔐 Setting file permissions..."
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache

# Install Node.js dependencies and build assets (if using Vite)
if [ -f package.json ]; then
    echo "📦 Installing Node.js dependencies..."
    npm install
    echo "🏗️ Building assets..."
    npm run build
fi

echo "✅ Deployment completed successfully!"
echo ""
echo "📋 Next steps:"
echo "1. Configure your web server (Apache/Nginx)"
echo "2. Setup SSL certificate"
echo "3. Test the application"
echo "4. Monitor logs in storage/logs/"
echo ""
echo "🌐 Your CV Maker is ready for production!"
