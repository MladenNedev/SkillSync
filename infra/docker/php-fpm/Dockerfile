FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpq-dev libonig-dev curl \
 && docker-php-ext-install pdo pdo_mysql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN echo "memory_limit=1G" > /usr/local/etc/php/conf.d/memory-limit.ini

# Create app user
RUN useradd -ms /bin/bash app

WORKDIR /var/www/html

# IMPORTANT: create required Laravel dirs
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

USER app

EXPOSE 9000
CMD ["php-fpm"]