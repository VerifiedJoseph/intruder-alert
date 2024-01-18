FROM composer:2.6.6 AS composer

COPY ./ /app
WORKDIR /app

# Run composer to install dependencies
RUN composer install \
  --optimize-autoloader \
  --no-interaction \
  --no-progress \
  --no-dev

FROM node:20.11.0-alpine3.18 AS node

COPY --from=composer ./app /app/
WORKDIR /app

# Install dependencies with npm
RUN npm ci

# Build (esbuild)
RUN npm run build

FROM php:8.2.14-fpm-alpine3.18

# Install packages
 RUN apk add --no-cache \
  nginx \
  supervisor

# Copy nginx config
COPY /docker/config/nginx.conf /etc/nginx/nginx.conf

# Copy php-fpm config
COPY /docker/config/fpm-pool.conf /usr/local/etc/php-fpm.d/zz-docker.conf

# Copy supervisord config
COPY /docker/config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy code
COPY --from=node /app/dist/ /app/
WORKDIR /app

# Create needed folders
RUN mkdir -p /app/backend/data/geoip2 /app/backend/data/logs

# Set owner
RUN chown www-data:www-data /app/data.php /app/index.html && chown -R www-data:www-data /app/static/

# Create symlink for php
RUN ln -s /usr/bin/php82 /usr/bin/php

# Create symlink for php-fpm
RUN ln -s /usr/sbin/php-fpm82 /usr/sbin/php-fpm

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
