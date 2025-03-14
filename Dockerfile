# Use an official PHP image as a parent image
FROM php:8.2-apache

# Install necessary PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN apt-get update; \
    apt-get install -y libmagickwand-dev; \
    pecl install imagick; \
    docker-php-ext-enable imagick;

# Add this line before the WORKDIR command in your Dockerfile
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files to the container
COPY . /var/www/html/

# Set file permissions for logs directory
RUN chmod -R 777 /var/www/html/logs

# Expose port 80
EXPOSE 80

# FROM php:7.4-apache

# # Install mysqli extension
# RUN docker-php-ext-install mysqli

# # Enable Apache mod_rewrite for .htaccess support
# RUN a2enmod rewrite

# # Set the working directory to the default Apache web root
# WORKDIR /var/www/html

# # Copy PHP files into the container
# COPY ./php /var/www/html

# # Adjust permissions for the web root
# RUN chown -R www-data:www-data /var/www/html
