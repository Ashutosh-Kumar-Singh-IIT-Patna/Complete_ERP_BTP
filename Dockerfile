# Use an official PHP image as a parent image
FROM php:8.2-apache

# Install necessary PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

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
