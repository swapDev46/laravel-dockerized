FROM php:8.5-cli

WORKDIR /var/www/html/swapshy-projects

# System packages + PHP extensions
RUN apt-get update \
    && apt-get install -y \
        libzip-dev \
        unzip \
        git \
    && docker-php-ext-install \
        pdo_mysql \
        zip \
        pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy Composer files first for Docker layer caching
COPY composer.json composer.lock ./

# Copy .env from docker-env file
COPY .docker-env .env

# Install PHP dependencies
RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# Copy application source
COPY . .

# Run Laravel Composer scripts after artisan exists
RUN composer run-script post-autoload-dump

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]