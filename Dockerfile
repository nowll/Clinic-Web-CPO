FROM php:8.1.2-apache

# Install dependencies for LDAP and other common extensions
RUN apt-get update && apt-get install -y \
    libldap2-dev \
    libzip-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Enable mysqli and ldap extensions
RUN docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ \
    && docker-php-ext-install mysqli ldap pdo pdo_mysql zip

# Copy a custom php.ini if necessary
COPY php.ini /usr/local/etc/php/

# Enable mod_rewrite for Apache
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html