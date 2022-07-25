#!/bin/bash
set -e

echo "Start entrypoint file"

echo "APACHE_REMOTE_IP_HEADER: ${APACHE_REMOTE_IP_HEADER}"
echo "APACHE_REMOTE_IP_TRUSTED_PROXY: ${APACHE_REMOTE_IP_TRUSTED_PROXY}"
echo "APACHE_REMOTE_IP_INTERNAL_PROXY: ${APACHE_REMOTE_IP_INTERNAL_PROXY}"

echo "Setup TZ"
php -r "date_default_timezone_set('${TZ}');"
php -r "echo date_default_timezone_get();"

echo "Install composer"
composer install && composer dump-auto

echo "Update artisan"
php artisan key:generate

echo "Run NPM DEV"
npm run dev

exit 0

# wait until all processes end (wait returns 0 retcode)
while :; do
    if wait; then
        break
    fi
done
