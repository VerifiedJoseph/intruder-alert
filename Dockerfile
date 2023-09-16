FROM composer:2.6.2 AS composer
ENV IA_VERSION=1.0.0

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
  curl=~8.2.1-r0 \
  nginx=~1.24.0-r6 \
  supervisor=~4.2.5-r2

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
