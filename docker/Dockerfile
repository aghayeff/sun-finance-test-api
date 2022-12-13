FROM php:8.1-apache

RUN a2enmod rewrite

WORKDIR /var/www/html

# Install Composer from latest Docker image
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Section to install PHP
RUN apt-get update -y && apt-get install -y \
    # PHP Modules
curl libicu-dev libxml2-dev libzip-dev zip unzip zlib1g-dev

    # Support internationalization
RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl

    # Install Postgre PDO
RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql
    # Support XML data format
RUN docker-php-ext-install xml

    # Support (un-)packing files using/via PHP
RUN docker-php-ext-install zip

    # Copy and enable site config
COPY docker/apache/100-site.conf /etc/apache2/sites-enabled/000-default.conf