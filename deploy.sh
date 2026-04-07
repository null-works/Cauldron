#!/bin/bash
# Deploy script for Winter CMS on Hestia VPS
# Usage: ssh into server, cd to web root, run ./deploy.sh

set -e

echo "Pulling latest changes..."
git pull origin main

echo "Installing dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "Running migrations..."
php artisan winter:up

echo "Clearing cache..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear

echo "Setting permissions..."
# Detect the site owner from the parent directory
SITE_USER=$(stat -c '%U' .)
SITE_GROUP=$(stat -c '%G' .)
chown -R "${SITE_USER}:${SITE_GROUP}" storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "Deploy complete."
