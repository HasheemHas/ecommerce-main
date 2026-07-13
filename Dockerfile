# Use the official PHP 8.2 image with Apache
FROM php:8.2-apache

# Install system dependencies (including Python 3 and pip)
RUN apt-get update && apt-get install -y \
    python3 \
    python3-pip \
    python3-venv \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

# Install the mysqli and GD PHP extensions
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy application files
COPY . /var/www/html/

# Ensure the upload target exists even when the repository has no uploaded
# product files yet.
RUN mkdir -p /var/www/html/admin/products/uploaded_photos

# Install Python requirements in a virtual environment
RUN python3 -m venv /opt/venv
ENV PATH="/opt/venv/bin:$PATH"
RUN pip install --no-cache-dir -r /var/www/html/backend/ai_microservice/requirements.txt

# Set permissions for Apache
RUN chown -R www-data:www-data /var/www/html

# Copy and set up the entrypoint script
COPY entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose port 80 (Apache default)
EXPOSE 80

# Run entrypoint script
ENTRYPOINT ["entrypoint.sh"]
