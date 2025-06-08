FROM php:8.2-fpm-alpine

# Define build arguments for user ID and group ID
ARG UID
ARG GID

# Set environment variables for user and group IDs
ENV UID=${UID}
ENV GID=${GID}

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Install Xdebug extension for PHP
RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug-3.4.3 \
    && docker-php-ext-enable xdebug

RUN addgroup -g ${GID} --system khalid \
    && adduser -G khalid --system -D -s /bin/sh -u ${UID} khalid \
    && sed -i "s/user = www-data/user = khalid/g" /usr/local/etc/php-fpm.d/www.conf \
    && sed -i "s/group = www-data/group = khalid/g" /usr/local/etc/php-fpm.d/www.conf \
    && echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "error_reporting=E_ALL" > /usr/local/etc/php/conf.d/error_reporting.ini

USER khalid

EXPOSE 9000