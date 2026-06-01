FROM composer:2.10.0@sha256:1b73755de4f19775ba6087fd5313664493e06fab72b6fc27dc2044e87bb7c4c3 AS composer

COPY ./ /app
WORKDIR /app

# Run composer to install dependencies
RUN composer install \
  --optimize-autoloader \
  --no-interaction \
  --no-progress \
  --no-dev

FROM node:22.22.3-alpine3.23@sha256:968df39aedcea65eeb078fb336ed7191baf48f972b4479711397108be0966920 AS node

COPY --from=composer ./app /app/
WORKDIR /app

# Install dependencies with npm
RUN npm ci --ignore-scripts

# Build (esbuild)
RUN npm run build

FROM php:8.3.31-fpm-alpine3.23@sha256:1b440e9804209491713035c4859d434f55e5cf8b0fb8c88a58f2f73d8e18b420
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
