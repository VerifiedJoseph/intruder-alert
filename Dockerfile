FROM composer:2.8 AS composer

COPY ./ /app
WORKDIR /app

# Run composer to install dependencies
RUN composer install \
  --optimize-autoloader \
  --no-interaction \
  --no-progress \
  --no-dev

FROM node:22.18-alpine3.22 AS node

COPY --from=composer ./app /app/
WORKDIR /app

# Install dependencies with npm
RUN npm ci

# Build (esbuild)
RUN npm run build

FROM php:8.3.24-fpm-alpine3.22
ENV IA_DOCKER=true

# Install packages
RUN apk add --no-cache \
  bash \
  curl \
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

# Copy daemon bash script
COPY /docker/scripts/daemon.sh /app/backend/daemon.sh

WORKDIR /app

# Create needed folders
RUN mkdir -p /app/backend/data/geoip2 /app/backend/data/logs

# Set owner
RUN chown www-data:www-data /app/data.php /app/index.html && chown -R www-data:www-data /app/static/

# Create symlink for php
RUN ln -s /usr/bin/php82 /usr/bin/php

# Create symlink for php-fpm
RUN ln -s /usr/sbin/php-fpm82 /usr/sbin/php-fpm

# php-fpm hleath check
HEALTHCHECK --interval=60s --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
