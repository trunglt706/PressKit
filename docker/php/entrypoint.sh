#!/usr/bin/env sh
set -e

if [ -f /var/www/html/artisan ]; then
  # Ensure runtime directories exist and are writable for Laravel.
  mkdir -p \
    /var/www/html/storage/logs \
    /var/www/html/storage/framework/cache \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/bootstrap/cache

  chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
  chmod -R a+rwX /var/www/html/storage /var/www/html/bootstrap/cache || true

  # Keep log file writable even when bind-mounted from host.
  touch /var/www/html/storage/logs/laravel.log || true
  chown www-data:www-data /var/www/html/storage/logs/laravel.log || true
  chmod 666 /var/www/html/storage/logs/laravel.log || true

  # Non-fatal startup helpers for local/dev convenience.
  php /var/www/html/artisan storage:link --force >/dev/null 2>&1 || true
  php /var/www/html/artisan optimize:clear >/dev/null 2>&1 || true

  # Re-apply ownership after artisan writes runtime files.
  chown -R www-data:www-data /var/www/html/bootstrap/cache /var/www/html/storage/framework/cache || true
  chmod -R a+rwX /var/www/html/bootstrap/cache /var/www/html/storage/framework/cache || true
fi

exec "$@"
