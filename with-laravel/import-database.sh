#!/bin/bash

# Database Import Script for CV Maker
# This script imports the existing MySQL database

echo "🗄️ Importing CV Maker database..."

# Check if database file exists
if [ ! -f luxdemoestate_cv.sql ]; then
    echo "❌ luxdemoestate_cv.sql file not found!"
    exit 1
fi

# Get database credentials from .env file
if [ ! -f .env ]; then
    echo "❌ .env file not found! Please create it first."
    exit 1
fi

# Extract database credentials from .env
DB_HOST=$(grep DB_HOST .env | cut -d '=' -f2)
DB_PORT=$(grep DB_PORT .env | cut -d '=' -f2)
DB_DATABASE=$(grep DB_DATABASE .env | cut -d '=' -f2)
DB_USERNAME=$(grep DB_USERNAME .env | cut -d '=' -f2)
DB_PASSWORD=$(grep DB_PASSWORD .env | cut -d '=' -f2)

echo "📊 Database: $DB_DATABASE"
echo "🏠 Host: $DB_HOST:$DB_PORT"
echo "👤 User: $DB_USERNAME"

# Import the database
echo "📥 Importing database..."
mysql -h $DB_HOST -P $DB_PORT -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE < luxdemoestate_cv.sql

if [ $? -eq 0 ]; then
    echo "✅ Database imported successfully!"
    echo ""
    echo "📋 Next steps:"
    echo "1. Run: php artisan migrate"
    echo "2. Run: php artisan db:seed (optional)"
    echo "3. Test the application"
else
    echo "❌ Database import failed!"
    echo "Please check your database credentials and connection."
    exit 1
fi
