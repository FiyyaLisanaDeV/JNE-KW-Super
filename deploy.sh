#!/bin/bash
set -e

cd /var/www/logistic-vendor
COMPOSER_ALLOW_SUPERUSER=1 composer update --no-dev --optimize-autoloader
npm ci
npm run build
php artisan key:generate --force
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan filament:assets

chown -R www-data:www-data /var/www/logistic-vendor
chmod -R 775 /var/www/logistic-vendor/storage
chmod -R 775 /var/www/logistic-vendor/bootstrap/cache

ln -sf /etc/nginx/sites-available/kurir.fiyya.cloud /etc/nginx/sites-enabled/
nginx -t
systemctl reload nginx
