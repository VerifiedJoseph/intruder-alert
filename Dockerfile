FROM composer:2.9.5@sha256:743aebe48ca67097c36819040633ea77e44a561eca135e4fc84c002e63a1ba07 AS composer

COPY ./ /app
WORKDIR /app

# Run composer to install dependencies
RUN composer install \
  --optimize-autoloader \
  --no-interaction \
  --no-progress \
  --no-dev

FROM node:22.22-alpine3.23@sha256:4d64b49e6c891c8fc821007cb1cdc6c0db7773110ac2c34bf2e6960adef62ed3 AS node

COPY --from=composer ./app /app/
WORKDIR /app

# Install dependencies with npm
RUN npm ci --ignore-scripts

# Build (esbuild)
RUN npm run build

FROM php:8.3.30-fpm-alpine3.23@sha256:2127b967ef53d95fd84b3ccb15361d638c3c6dc72079123cce5467c642020a81
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

# php-fpm hleath check
HEALTHCHECK --interval=60s --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
