FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libicu-dev \
    git \
    unzip \
    && docker-php-ext-install \
    pgsql \
    bcmath \
    intl

WORKDIR /app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


COPY ./ /app/

RUN composer install



