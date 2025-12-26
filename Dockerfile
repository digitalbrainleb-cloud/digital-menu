FROM php:8.2-cli
RUN apt-get update && apt-get install -y zip unzip git libzip-dev && docker-php-ext-install pdo pdo_mysql
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN mkdir -p runtime web/assets && chmod -R 777 runtime web/assets
# Railway automatically sets PORT, use it directly
CMD php -S 0.0.0.0:$PORT public/index.php
