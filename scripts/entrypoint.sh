#!/bin/sh

# Ensure the nginx run directory exists
mkdir -p /run/nginx

# Render a fresh nginx config from template each boot
cp /etc/nginx/http.d/default.conf.template /etc/nginx/http.d/default.conf

# Dynamically set the port in the nginx configuration (default: 80)
LISTEN_PORT=${PORT:-80}
sed -i "s/LISTEN_PORT/${LISTEN_PORT}/g" /etc/nginx/http.d/default.conf

# Configure nginx request body size limit (default: 20m)
CLIENT_MAX_BODY_SIZE=${CLIENT_MAX_BODY_SIZE:-20m}
sed -i "s/CLIENT_MAX_BODY_SIZE/${CLIENT_MAX_BODY_SIZE}/g" /etc/nginx/http.d/default.conf

echo "Configuring PHP upload limits..."
cp /usr/local/etc/php/conf.d/zz-custom.ini.template /usr/local/etc/php/conf.d/zz-custom.ini
PHP_UPLOAD_MAX_FILESIZE=${PHP_UPLOAD_MAX_FILESIZE:-20M}
PHP_POST_MAX_SIZE=${PHP_POST_MAX_SIZE:-20M}
sed -i "s/PHP_UPLOAD_MAX_FILESIZE/${PHP_UPLOAD_MAX_FILESIZE}/g" /usr/local/etc/php/conf.d/zz-custom.ini
sed -i "s/PHP_POST_MAX_SIZE/${PHP_POST_MAX_SIZE}/g" /usr/local/etc/php/conf.d/zz-custom.ini

echo "Starting deployment scripts..."
/usr/local/bin/deploy.sh

echo "Starting PHP-FPM..."
php-fpm -D

echo "Starting Nginx on port ${LISTEN_PORT}..."
nginx -g 'daemon off;'
