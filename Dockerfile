FROM php:8.2-apache

# Install driver PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copy file index.php kamu ke dalam server
COPY . /var/www/html/

# Buka port 80 untuk web
EXPOSE 80