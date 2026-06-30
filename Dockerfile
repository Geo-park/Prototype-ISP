FROM webdevops/php-nginx:8.3-alpine

# Set environment variables
ENV WEB_DOCUMENT_ROOT=/app/public
ENV COMPOSER_ALLOW_SUPERUSER=1

# Tentukan working directory di dalam container
WORKDIR /app

# Salin semua file proyek ke container
COPY . /app

# Jalankan composer install
RUN composer install --no-dev --optimize-autoloader

# Expose port default Nginx
EXPOSE 80
