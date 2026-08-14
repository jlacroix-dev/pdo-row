ARG PHP_VERSION=8.2
FROM php:${PHP_VERSION}-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
      autoconf \
      git \
      curl \
      zip \
      unzip \
      libzip-dev \
      sqlite3 \
      libsqlite3-dev \
      default-mysql-client \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_sqlite pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json ./

RUN composer install --prefer-dist --no-interaction
