FROM dunglas/frankenphp

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

## install dependencies
RUN chmod +x /usr/local/bin/install-php-extensions \
    && apt-get update && apt-get upgrade -y && apt-get install -y \
      nano \
      git \
      unzip \
      supervisor \
      cron \
      sudo \
      wget \
    && install-php-extensions \
      pdo_mysql \
      intl \
      opcache \
      zip \
      sockets \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get clean

## install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1

## Install REDIS to test cache pool
RUN pecl install redis && \
    docker-php-ext-enable redis


COPY . /app/public
