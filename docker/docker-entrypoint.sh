#!/bin/sh
set -e

# Si no vienes con PORT de Railway, usar 8080 por defecto
: "${PORT:=8080}"

# Renderizar la plantilla de nginx con el PORT definido
envsubst '\$PORT' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf

# Asegurar permisos
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache || true

# Arrancar php-fpm (puerto 9000 por defecto de la imagen)
php-fpm -D

# Arrancar nginx
exec nginx -g 'daemon off;'
