#!/bin/bash

# Optional sleep to wait for other services (like DB)
sleep 10

# Run database migrations and seed
echo "Running migrations and seeding..."
php artisan migrate --seed

# Start the queue worker
echo "Starting queue worker..."
php artisan queue:work --daemon &

# Start the Laravel scheduler in loop
echo "Starting Laravel scheduler..."
while true; do
  php artisan schedule:run >> /dev/null 2>&1
  sleep 60
done &

# Start the Laravel development server (or PHP-FPM if in production)
echo "Starting Laravel server..."
exec php artisan serve --host=0.0.0.0 --port=8000
