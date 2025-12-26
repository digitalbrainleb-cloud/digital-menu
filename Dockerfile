FROM php:8.2-cli
WORKDIR /app
COPY . .

RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Copy start script
COPY start.sh /app/start.sh
RUN chmod +x /app/start.sh

EXPOSE 8080
CMD ["/app/start.sh"]