FROM php:8.2-fpm

RUN apt-get update \
    && apt-get install -y wget git zip

RUN wget -O /usr/local/bin/install-php-extensions https://git.3hop.eu/Public/install-php-extensions/raw/branch/master/install-php-extensions
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo_mysql intl apcu opcache zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash -
RUN apt-get install -y nodejs
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
RUN echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list
RUN apt-get update && apt-get install -y yarn

RUN usermod -u 1000 www-data

WORKDIR /var/www/html

CMD ["php-fpm"]
# CMD ["php-fpm", "./docker/docker.sh"]

EXPOSE 9000