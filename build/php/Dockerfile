FROM php:fpm
RUN apt-get update && apt-get install -y libmcrypt-dev

# lumen packages
RUN docker-php-ext-install mbstring tokenizer