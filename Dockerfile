FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git curl zip unzip nodejs npm \
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev
RUN npm install && npm run build
RUN php artisan key:generate

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]