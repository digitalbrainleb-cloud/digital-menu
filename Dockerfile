FROM php:8.2-cli
WORKDIR /app
COPY . .
RUN apt-get update && apt-get install -y zip unzip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --no-interaction
CMD php -S 0.0.0.0:8080 public/index.php
