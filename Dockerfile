FROM php:7.4-fpm
# USER root

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

RUN apt-get update && apt-get install -y build-essential \
    locales \
    zip \
    curl
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install json iconv
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g 1000 www && \
    useradd -u 1000 -ms /bin/bash -g www www

COPY . /var/www
COPY --chown=www:www . /var/www
RUN chown -R www:www /var/www

USER www
RUN composer -n --no-progress install
EXPOSE 9000
CMD ["php-fpm"]