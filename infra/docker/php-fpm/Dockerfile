FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpq-dev libonig-dev curl \
&&  docker-php-ext-install pdo pdo_mysql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN echo "memory_limit=1G" > /usr/local/etc/php/conf.d/memory-limit.ini

RUN useradd -ms /bin/bash app && chown -R app:app /var/www/
USER app



WORKDIR /var/www/html

EXPOSE 9000
CMD ["php-fpm"]