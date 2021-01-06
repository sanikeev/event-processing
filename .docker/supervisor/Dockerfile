FROM php:7.4 as supervisor

MAINTAINER Anikeev Sergey <keshbuk@gmail.com>

RUN apt-get update && \
    apt-get install -y \
    supervisor \
    openssl \
    git \
    unzip \
    librabbitmq-dev \
    libssh-dev \
    && docker-php-ext-install \
        bcmath \
        sockets \
    && pecl install amqp \
    && docker-php-ext-enable amqp

RUN mkdir -p /var/log/supervisor
COPY supervisord.conf /etc/supervisor/supervisord.conf

EXPOSE 8089

WORKDIR /var/www/symfony

CMD ["/usr/bin/supervisord"]
