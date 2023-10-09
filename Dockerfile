FROM composer:2.6.5 AS composer

COPY ./ /app
WORKDIR /app

# Run composer to install dependencies
RUN composer install \
  --optimize-autoloader \
  --no-interaction \
  --no-progress \
  --no-dev

FROM node:18.18.0-alpine3.18 AS node

COPY --from=composer ./app /app/
WORKDIR /app

# Install dependencies with npm
RUN npm ci

# Build (esbuild)
RUN npm run build

FROM alpine:3.18.4

# Install packages
 RUN apk add --no-cache \
  curl \
  nginx \
  supervisor \
  php82 \
  php82-curl \
  php82-phar \
  php82-fpm

# Copy nginx config
COPY --chown=nobody /docker/config/nginx.conf /etc/nginx/nginx.conf

# Copy php-fpm config
COPY --chown=nobody /docker/config/fpm-pool.conf /etc/php82/php-fpm.d/www.conf

# Copy supervisord config
COPY --chown=nobody /docker/config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy code
COPY --chown=nobody --from=node /app/dist/ /app/

# Create needed folders
RUN mkdir -p /app/backend/data/geoip2 /app/backend/data/logs

# Make files accessable to nobody user
RUN chown -R nobody.nobody /run /app /var/lib/nginx /var/log/nginx

# Create symlink for php
RUN ln -s /usr/bin/php82 /usr/bin/php

# Create symlink for php-fpm
RUN ln -s /usr/sbin/php-fpm82 /usr/sbin/php-fpm

USER nobody
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
