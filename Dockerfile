FROM php:7.4-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Enable Apache mod_rewrite for .htaccess support
RUN a2enmod rewrite

# Set the working directory to the default Apache web root
WORKDIR /var/www/html

# Copy PHP files into the container
COPY ./php /var/www/html

# Adjust permissions for the web root
RUN chown -R www-data:www-data /var/www/html
