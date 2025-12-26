FROM php:8.2-cli
RUN docker-php-ext-install pdo pdo_mysql
WORKDIR /app
COPY . .
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN mkdir -p runtime web/assets && chmod -R 777 runtime web/assets
EXPOSE 8080
CMD ["php", "-S", "0.0.0.0:8080", "public/index.php"]
