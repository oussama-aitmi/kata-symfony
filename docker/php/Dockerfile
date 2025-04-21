FROM php:8.1-fpm

RUN apt-get update \
    && apt-get install -y zlib1g-dev g++ git libicu-dev zip libpq-dev libzip-dev zip \
	&& docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install intl opcache pdo pdo_mysql pdo_pgsql pgsql \
    && docker-php-ext-enable pdo_pgsql  \
	&& docker-php-ext-configure zip \
	&& docker-php-ext-install zip

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Install composer
CMD composer install ;  php-fpm

# Install symfony (facultatif)
RUN curl -sS https://get.symfony.com/cli/installer | bash 
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

EXPOSE 9000