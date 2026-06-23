#!/bin/bash
# Railway start script for Laravel

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Run migrations
php artisan migrate --force

# Cache config & routes for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage symlink
php artisan storage:link 2>/dev/null || true

# Start server
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
