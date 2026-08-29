# ==========================================
# Stage 1: Build Frontend Assets (Vite)
# ==========================================
FROM node:20-alpine AS frontend-builder

WORKDIR /app

COPY package*.json vite.config.js ./
RUN npm ci

COPY resources/ ./resources/
COPY public/ ./public/

RUN npm run build

# ==========================================
# Stage 2: Production PHP-FPM + Nginx Container
# ==========================================
FROM php:8.2-fpm-alpine

# Set working directory
WORKDIR /var/www/html

# Install system dependencies & PHP extension build tools
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    git \
    zip \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    mariadb-client \
    bash

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        intl \
        zip \
        opcache

# Get latest Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy application source code
COPY . /var/www/html

# Copy built frontend assets from Stage 1
COPY --from=frontend-builder /app/public/build /var/www/html/public/build

# Install PHP dependencies for production
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copy Custom Configurations
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/custom-php.ini
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Set executable permissions for entrypoint and fix storage ownership
RUN chmod +x /usr/local/bin/entrypoint.sh \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public

# Cloud Run default port
EXPOSE 8080

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
