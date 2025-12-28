FROM php:8.2-apache

# Enable Apache rewrite
RUN a2enmod rewrite

# Install MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy all project files to Apache root
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
