FROM composer:2.5.8 AS composer

# Copy application
COPY ./ /app

WORKDIR /app

# Run composer to install dependencies
RUN composer install \
  --optimize-autoloader \
  --no-interaction \
  --no-progress \
  --no-dev

FROM php:8.2.10-fpm-alpine3.18

# Install packages
 RUN apk add --no-cache \
  curl \
  nginx \
  supervisor

# Copy entrypoint script
COPY --chown=nobody /docker/scripts/entrypoint.sh /etc/entrypoint.sh

# Copy nginx config
COPY --chown=nobody /docker/config/nginx.conf /etc/nginx/nginx.conf

# Copy supervisord config
COPY --chown=nobody /docker/config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy code
COPY --chown=nobody --from=composer /app/ /app/

# Make files accessable to nobody user
RUN chown -R nobody.nobody /run /app/ /var/lib/nginx/logs/

# Remove setup files
RUN rm -r /app/docker
RUN rm /app/composer.*

USER nobody
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
