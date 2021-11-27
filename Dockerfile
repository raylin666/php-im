FROM raylin666/php:7.4-fpm-swoole4-v1.0.3
MAINTAINER raylin666 <"1099013371@qq.com">

WORKDIR /var/www/html

ADD ./ /var/www/html
COPY ./init.d/php.ini /usr/local/etc/php/php.ini

RUN composer install --no-dev -o

EXPOSE 10000 10001

ENTRYPOINT ["php", "bin/hyperf.php", "start"]
