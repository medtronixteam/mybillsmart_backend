#!/bin/bash

# Wait for the database to be ready (optional but useful if DB is in another container)
# Sleep for 10 seconds to allow database connections to establish
sleep 10

# Run database migrations and seed
echo "Running migrations and seeding..."
php artisan migrate --seed

# Run the Laravel queue worker in background
echo "Starting queue worker..."
php artisan queue:work --daemon &

# Run the Laravel scheduler in background
echo "Starting Laravel scheduler..."
php artisan schedule:run &

# Finally, start the Laravel development server
echo "Starting Laravel server..."
exec php artisan serve --host=0.0.0.0 --port=8000
