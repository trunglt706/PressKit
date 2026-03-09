#!/usr/bin/env sh
set -e

touch /var/log/cron.log
chown www-data:www-data /var/log/cron.log
chmod 664 /var/log/cron.log
cron -f
