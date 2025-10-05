FROM php:8.2-apache

# System packages for PHP extensions and tools
RUN apt-get update \
	&& apt-get install -y --no-install-recommends libpng-dev zlib1g-dev libzip-dev unzip \
	&& rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-configure gd --with-jpeg --with-freetype || true \
	&& docker-php-ext-install -j"$(nproc)" pdo pdo_mysql gd zip

# Enable Apache mod_rewrite just in case we extend later
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copy app code into image (for stable run without bind mount)
COPY . /var/www/html
RUN mkdir -p /var/www/html/uploads && chmod 777 /var/www/html/uploads
RUN mkdir -p /var/www/html/tmp && chmod 777 /var/www/html/tmp

# Optional: seed script could be added here if present (seed_db.sh)
# COPY seed_db.sh /var/www/html/seed_db.sh
# RUN chmod +x /var/www/html/seed_db.sh

# Install Composer and PHPSpreadsheet dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && cd /var/www/html \
    && composer update --no-dev --no-interaction --prefer-dist

# Seed database: run manually in the container if needed
#   docker exec -it pdpa_web php /var/www/html/seed_db.php
