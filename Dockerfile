# Multi-stage Dockerfile for Railway
# Builder stage: use Composer image to install PHP dependencies
FROM composer:2 AS builder
WORKDIR /app

# Copy composer files first to leverage caching
# Use a glob to copy composer.json and composer.lock if present (avoids build failure when lock is absent)
COPY composer.* ./

# Install dependencies (no dev) and optimize autoloader
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader --no-scripts

# Copy the rest of the application
COPY . .

# Ensure autoloader is optimized
RUN composer dump-autoload --optimize

# Runtime stage
FROM php:8.2-cli
WORKDIR /app

# Install system dependencies and PHP extensions commonly needed by Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
 && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath gd xml \
 && rm -rf /var/lib/apt/lists/*

# Copy application from builder
COPY --from=builder /app /app

# Ensure storage and bootstrap/cache are writable
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache || true

ENV PORT 8080
ENV APP_ENV production

# Do not run config cache at build time; run at container start if APP_KEY present
# Start command: cache config if APP_KEY exists, then serve with artisan on $PORT
CMD ["sh", "-lc", "if [ -n \"$APP_KEY\" ]; then php artisan config:cache || true; fi; php artisan migrate --force 2>/dev/null || true; php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]
