# Use the official PHP image with PHP-FPM
FROM php:8.3-fpm

# Set the working directory
WORKDIR /var/www

# Install dependencies and libraries
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libssl-dev \
    pkg-config \
    libmcrypt-dev \
    libcurl4-openssl-dev \
    libz-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd sockets \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Copy application code
COPY . /var/www

# Set proper permissions for Laravel directories
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/vendor


# Switch to www-data user
USER www-data

# Expose port 9000
EXPOSE 9000

# Start PHP-FPM server
CMD ["php-fpm"]
