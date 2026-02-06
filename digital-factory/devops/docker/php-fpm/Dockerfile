# php-fpm

ARG version

FROM php:${version}

CMD ["php-fpm"]

RUN docker-php-ext-install pdo pdo_mysql mysqli

EXPOSE 9000