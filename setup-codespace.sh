#!/bin/bash

# Fitness Tracker Laravel Application Setup Script
# This script sets up the development environment for GitHub Codespaces

echo "🏋️ Setting up Fitness Tracker Laravel Application..."

# Update system packages
echo "📦 Updating system packages..."
sudo apt-get update && sudo apt-get upgrade -y

# Install additional PHP extensions if needed
echo "🔧 Installing PHP extensions..."
sudo apt-get install -y php-sqlite3 php-xml php-curl php-mbstring php-zip php-gd

# Copy environment file
echo "⚙️ Setting up environment configuration..."
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Update .env file with SQLite configuration for Codespaces
echo "🔧 Configuring database settings..."
sed -i 's/DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env
sed -i 's/DB_DATABASE=.*/DB_DATABASE=database\/database.sqlite/' .env

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-interaction

# Run database migrations and seeders
echo "🗄️ Setting up database..."
php artisan migrate:fresh --seed

# Set proper permissions
echo "🔐 Setting file permissions..."
sudo chown -R $USER:$USER storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "✅ Setup complete! Your Fitness Tracker application is ready."
echo "🚀 Start the development server with: php artisan serve"
echo "🌐 Access the application at: http://localhost:8000/workouts"
