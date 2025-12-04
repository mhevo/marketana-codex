FROM php:8.3-fpm-alpine

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html

# Copy existing application directory contents (mounted at runtime)
