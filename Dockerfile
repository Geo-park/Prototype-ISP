FROM richarvey/nginx-php-fpm:latest

# Salin semua file proyek ke container
COPY . /var/www/html

# Konfigurasi environment image
ENV WEBROOT /var/www/html/public
ENV COMPOSER_ALLOW_SUPERUSER 1

# Install dependency PHP (composer)
RUN composer install --no-dev --optimize-autoloader

# Expose port default Nginx
EXPOSE 80
