FROM php:8.2-fpm-alpine

# =====================================================================
# PHP-FPM Dockerfile for LIKIZOPRO
# =====================================================================

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    oniguruma-dev \
    autoconf \
    gcc \
    make \
    docker-cli

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        zip \
        intl \
        mbstring \
        bcmath \
        opcache \
        gd

# Install Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/likizopro

# Copy application files
COPY . /var/www/likizopro/

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set permissions
RUN chown -R www-data:www-data /var/www/likizopro \
    && chmod -R 755 /var/www/likizopro \
    && chmod -R 777 /var/www/likizopro/writable \
    && chmod -R 777 /var/www/likizopro/public/uploads 2>/dev/null || true

# Expose port
EXPOSE 9000

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=40s --retries=3 \
    CMD php-fpm -t || exit 1

# Start PHP-FPM
CMD ["php-fpm"]
