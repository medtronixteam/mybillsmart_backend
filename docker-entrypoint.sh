#!/bin/bash

# Wait for DB (optional but useful)
sleep 10

echo "Running fresh migrations and seeding..."
php artisan migrate:fresh --seed || exit 1

echo "Starting queue worker..."
php artisan queue:work --daemon &

echo "Starting Laravel scheduler..."
while true; do
  php artisan schedule:run >> /dev/null 2>&1
  sleep 60
done &

echo "Starting Laravel server..."
exec php artisan serve --host=0.0.0.0 --port=8000
