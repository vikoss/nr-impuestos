# Stage: build frontend (Vite -> public/build)
FROM node:18-alpine AS frontend_build
WORKDIR /app
COPY package.json package-lock.json* yarn.lock* pnpm-lock.yaml* ./
RUN npm ci --no-audit --no-fund
COPY vite.config.js ./
COPY tailwind.config.js postcss.config.js ./
COPY resources ./resources
RUN npm run build

# Stage: base PHP con extensiones instaladas (reutilizable)
FROM php:8.2-fpm-bullseye AS php_base
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1
WORKDIR /var/www/html
RUN apt-get update && apt-get install -y --no-install-recommends \
    ca-certificates curl git unzip build-essential libzip-dev zlib1g-dev \
    libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libicu-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j"$(nproc)" gd zip pdo_mysql mbstring bcmath intl pcntl exif opcache \
  && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Stage: instalar dependencias PHP (vendor) sin scripts
FROM php_base AS php_vendor
WORKDIR /var/www/html
COPY composer.json composer.lock* ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader --no-scripts

# Stage final: runtime (php-fpm + nginx)
FROM php_base AS app
WORKDIR /var/www/html

# Instalar nginx y envsubst
RUN apt-get update && apt-get install -y --no-install-recommends nginx gettext-base \
  && rm -rf /var/lib/apt/lists/*

# Traer vendor y composer
COPY --from=php_vendor /usr/bin/composer /usr/bin/composer
COPY --from=php_vendor /var/www/html/vendor ./vendor

# Copiar código y assets
COPY . .
COPY --from=frontend_build /app/public/build ./public/build

# Copiar plantilla nginx y entrypoint
COPY docker/nginx/default.conf.template /etc/nginx/conf.d/default.conf.template
COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Permisos mínimos
RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# No exponemos 9000 aquí porque nginx servirá HTTP en $PORT (ej: 8080). FPM queda interno en 9000.
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
