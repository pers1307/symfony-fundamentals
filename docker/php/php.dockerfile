FROM php:8.2.6-fpm-bullseye

# make sure apt is up to date
RUN apt-get update --fix-missing
RUN apt-get update \
    && apt-get -y install curl build-essential libssl-dev zlib1g-dev libpng-dev libjpeg-dev libfreetype6-dev libicu-dev \
    libmagickwand-dev libzip-dev zip

RUN docker-php-ext-install gd pdo pdo_mysql zip intl

RUN pecl install xdebug-3.2.2  docker-php-ext-enable xdebug

COPY my.ini $PHP_INI_DIR/conf.d/x-my.ini
COPY ll /usr/local/bin/ll

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN curl https://getcomposer.org/download/2.5.4/composer.phar --output /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

RUN mkdir /var/www/.composer
RUN chown www-data:www-data /var/www/.composer

RUN apt-get autoclean && rm -r /var/lib/apt/lists/*

VOLUME /var/www/.composer

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt-get -y install symfony-cli

# Node.js
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get -y install nodejs && npm install --global yarn

RUN apt-get autoclean && rm -r /var/lib/apt/lists/*
RUN mkdir -m 777 -p /var/www/.cache/yarn

RUN touch /var/www/.yarnrc
RUN chown www-data:www-data /var/www/.yarnrc
# Пока так
RUN chmod 777 /var/www/.yarnrc

RUN mkdir /var/www/.symfony5
RUN chown www-data:www-data /var/www/.symfony5
# Пока так
RUN chmod 777 /var/www/.symfony5

VOLUME /var/www/.symfony5

RUN touch /var/www/.gitconfig
RUN chown www-data:www-data /var/www/.gitconfig
# Пока так
RUN chmod 777 /var/www/.gitconfig

RUN git config --global user.email "skulines@mail.ru"
RUN git config --global user.name "pers1307"

#VOLUME /var/www/.gitconfig

RUN usermod -u 1000 www-data
USER 1000

CMD ["php-fpm"]
EXPOSE 9000
