FROM alpine:latest
RUN apk --no-cache add nginx php7 php7-fpm php7-opcache
WORKDIR /var/www/uuids
COPY . .
COPY docker-conf/uuids.conf /etc/nginx/sites-available/
RUN ln -s /etc/nginx/sites-available/uuids.conf /etc/nginx/sites-enabled/uuids.conf && rc-service nginx restart