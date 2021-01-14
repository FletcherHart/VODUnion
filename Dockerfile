FROM php:8-fpm-alpine

COPY composer.lock composer.json /var/www/

RUN docker-php-ext-install pdo pdo_mysql

EXPOSE 9000
CMD ["php-fpm"]