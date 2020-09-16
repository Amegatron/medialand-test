FROM php:7.4-fpm

USER root

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www
RUN groupadd -g 1000 www && \
    useradd -u 1000 -ms /bin/bash -g www www && \
    chown www:www /var/www

COPY --chown=www:www . /var/www

RUN apt-get update && apt-get install -y build-essential \
    locales \
    zip \
    curl && \
    apt-get clean && \
    docker-php-ext-install json iconv && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

USER www
RUN composer -n --no-progress install
EXPOSE 9000
CMD ["php-fpm"]