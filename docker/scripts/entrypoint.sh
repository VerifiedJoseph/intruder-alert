#!/bin/sh
php-fpm -D
nginx -g 'daemon off;'
