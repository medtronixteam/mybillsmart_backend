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

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Install Laravel dependencies
RUN composer install --no-interaction --optimize-autoloader

# Expose port 8000
EXPOSE 8000

# Start the application: run migrations, then queue:work, and then serve the Laravel app
CMD ["sh", "-c", "php artisan migrate --seed && php artisan queue:work --daemon && php artisan serve --host=0.0.0.0 --port=8000"]
