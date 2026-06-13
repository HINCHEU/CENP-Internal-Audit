FROM php:8.2-fpm

ARG user=www-data
WORKDIR /var/www

# System deps
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        curl \
        ca-certificates \
        zip \
        unzip \
        libzip-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libonig-dev \
        libxml2-dev \
        default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo \
        pdo_mysql \
        mbstring \
        zip \
        exif \
        pcntl \
        bcmath \
        gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Create application user
RUN useradd -G www-data,root -u 1000 -m ${user} || true

# Copy only composer files to leverage build cache
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev || true

# Copy application
COPY . /var/www

# Ensure directories exist and are writable
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache || true

USER ${user}

EXPOSE 9000

CMD ["php-fpm"]