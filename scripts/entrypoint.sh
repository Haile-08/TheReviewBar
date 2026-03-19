#!/bin/sh

# Ensure the nginx run directory exists
mkdir -p /run/nginx

# Dynamically set the port in the nginx configuration
# Default to 80 if PORT is not set
LISTEN_PORT=${PORT:-80}
sed -i "s/LISTEN_PORT/${LISTEN_PORT}/g" /etc/nginx/http.d/default.conf

echo "Starting deployment scripts..."
/usr/local/bin/deploy.sh

echo "Starting PHP-FPM..."
php-fpm -D

echo "Starting Nginx on port ${LISTEN_PORT}..."
nginx -g 'daemon off;'
