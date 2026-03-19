#!/bin/sh

if [ -z "${APP_KEY}" ]; then
    echo "ERROR: APP_KEY is not set. Add a Laravel APP_KEY secret in your deployment environment before starting the app." >&2
    exit 1
fi

# Run migrations
php artisan migrate --force

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
