# Use an official PHP image as base
FROM php:8.1-fpm

# Install necessary dependencies (including cron)
RUN apt-get update && apt-get install -y cron git unzip libpng-dev libjpeg-dev libfreetype6-dev

# Install Composer (if not already done)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www

# Copy your Laravel app
COPY . .

# Install dependencies using Composer
RUN composer install

# Set correct file permissions
RUN chown -R www-data:www-data /var/www

# Copy your entry point script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set entrypoint script as entry point
ENTRYPOINT ["docker-entrypoint.sh"]

# Default command (if needed, to run php artisan serve)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
