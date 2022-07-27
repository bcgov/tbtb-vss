#!/bin/bash

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


chmod 766 /var/www/html/probe-check.sh

echo "End entrypoint"
