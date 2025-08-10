#!/bin/bash
set -e

echo "=== ENTRYPOINT STARTED ==="

echo "Running migrations..."
php artisan migrate --force
echo "Migrations completed!"

echo "Setup queues and exchanges..."
php artisan amqp:setup
echo "Queues and exchanges setup completed!"

echo "Starting Supervisor..."
echo "=== ENTRYPOINT FINISHED, LAUNCHING SUPERVISOR ==="
exec /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf