# Use official PHP image with necessary extensions
FROM php:8.2-cli

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    zip \
    unzip \
    git \
    libonig-dev \
    libxml2-dev \
    libpng-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing Laravel project files
COPY . .

# Copy the docker-entrypoint.sh script
COPY docker-entrypoint.sh /usr/local/bin/

# Set permissions for the entrypoint script
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set the entrypoint to the script
ENTRYPOINT ["docker-entrypoint.sh"]

# Set permissions for Laravel storage and bootstrap directories
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Install Laravel dependencies
RUN composer install --no-interaction --optimize-autoloader

# Expose port 8000
EXPOSE 8000
