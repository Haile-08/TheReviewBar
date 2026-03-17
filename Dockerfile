FROM node:20-alpine AS frontend-builder
WORKDIR /app
COPY package.json pnpm-lock.yaml ./
RUN npm install -g pnpm && pnpm install
COPY . .
RUN pnpm run build

FROM php:8.4-fpm-alpine
WORKDIR /var/www/html

RUN apk add --no-cache \
    nginx \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    linux-headers

RUN docker-php-ext-install pdo_pgsql zip bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader --no-scripts --no-progress --prefer-dist

COPY . .
COPY --from=frontend-builder /app/public/build ./public/build

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

COPY docker/nginx.conf /etc/nginx/http.d/default.conf

COPY scripts/deploy.sh /usr/local/bin/deploy.sh
RUN chmod +x /usr/local/bin/deploy.sh

EXPOSE 80

CMD ["sh", "-c", "/usr/local/bin/deploy.sh && nginx && php-fpm"]
