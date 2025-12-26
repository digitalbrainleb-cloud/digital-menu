FROM php:8.2-cli
RUN apt-get update && apt-get install -y zip unzip git libzip-dev && docker-php-ext-install pdo pdo_mysql
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN mkdir -p runtime web/assets && chmod -R 777 runtime web/assets
# Check where yii file is located
RUN ls -la yii* 2>/dev/null || echo "No yii file in root"
RUN ls -la public/yii* 2>/dev/null || echo "No yii in public"
# Try different locations for yii command
RUN if [ -f "yii" ]; then php yii migrate --interactive=0; fi
RUN if [ -f "public/yii" ]; then php public/yii migrate --interactive=0; fi
EXPOSE 8080
CMD ["php", "-S", "0.0.0.0:8080", "public/index.php"]
