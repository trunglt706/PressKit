#!/usr/bin/env sh
set -e

if [ -f /var/www/html/artisan ]; then
  chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
fi

exec "$@"
