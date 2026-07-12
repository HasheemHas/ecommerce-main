# Use the official PHP 8.2 image with Apache
FROM php:8.2-apache

# Install the mysqli PHP extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Install GD library for image processing
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy the application source code
COPY . /var/www/html/

# Set proper ownership and permissions for Apache web server
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 (Apache default)
EXPOSE 80
