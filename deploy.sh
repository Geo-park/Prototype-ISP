#!/bin/sh
# Jalankan migrasi database otomatis saat startup
php /app/artisan migrate --force
