FROM php:8.5-apache

RUN a2enmod rewrite headers

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev \
  && docker-php-ext-install zip calendar \
  && rm -rf /var/lib/apt/lists/*

# Change Apache DocumentRoot to /var/www/html/public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . /var/www/html

RUN git config --global --add safe.directory /var/www/html

RUN composer install --no-interaction --no-progress --prefer-dist --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
