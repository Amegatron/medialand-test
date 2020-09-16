FROM php:7.4-fpm
USER root
RUN apt-get update && apt-get install -y build-essential \
    locales \
    zip \
    curl

RUN docker-php-ext-install opcache json iconv

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#RUN groupadd -g 1000 www && \
#    useradd -u 1000 -ms /bin/bash -g www www

RUN chown -R www:www /var/www

WORKDIR /var/www
COPY --chown=www:www . .
USER www
RUN composer -n --no-progress install
EXPOSE 9000
CMD ["php-fpm"]