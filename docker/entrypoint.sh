#!/bin/sh
set -e

echo "=========================================="
echo "==> Starting Laravel on Google Cloud Run"
echo "=========================================="

# Ensure directories exist and have proper permissions
mkdir -p /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache \
         /var/run/nginx

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create storage symlink
php artisan storage:link || true

# Run optimization caches if production
if [ "$APP_ENV" = "production" ]; then
    echo "==> Caching routes and views..."
    php artisan config:clear || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# Run database migrations only if database is configured
if [ "$RUN_MIGRATIONS" = "true" ] && [ -n "$DB_HOST" ]; then
    echo "==> Running database migrations..."
    php artisan migrate --force || echo "==> [WARN] Migration failed. Check DB connection."
fi

echo "==> Starting Supervisord (Nginx & PHP-FPM)..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
