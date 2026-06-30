#!/bin/sh
# Jalankan migrasi fresh untuk membersihkan tabel kotor dan seed data awal
php /app/artisan migrate:fresh --seed --force
