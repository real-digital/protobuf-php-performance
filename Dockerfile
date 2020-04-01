FROM php:7.4.3-cli-alpine3.11 as main

ENV PROTOC_VERSION=3.11.2-r1
ENV PROTOBUF_VERSION=3.11.4

# Install packages
RUN apk add --update --no-cache \
    # Some basic stuff
    git \
    unzip \
    shadow \
    zlib-dev \
    libtool \
    oniguruma-dev \
    libxml2-dev \
    libzip-dev \
    openssl-dev \
    autoconf \
    g++ \
    automake \
    make \
    linux-headers \
    ca-certificates \
    protoc=${PROTOC_VERSION}

# Install PHP extensions available in docker-php-ext-install; those are:
# bcmath bz2 calendar ctype curl dba dom enchant exif fileinfo filter ftp gd gettext gmp hash iconv imap interbase intl
# json ldap mbstring mysqli oci8 odbc opcache pcntl pdo pdo_dblib pdo_firebird pdo_mysql pdo_oci pdo_odbc pdo_pgsql
# pdo_sqlite pgsql phar posix pspell readline recode reflection session shmop simplexml snmp soap sockets sodium spl
# standard sysvmsg sysvsem sysvshm tidy tokenizer wddx xml xmlreader xmlrpc xmlwriter xsl zend_test zip
RUN docker-php-ext-install \
    bcmath \
    mbstring \
    zip

# Install PHP extensions unavailable in docker-php-ext-install
RUN pecl install protobuf-${PROTOBUF_VERSION} && docker-php-ext-enable protobuf

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
# make composer installs faster by parallel downloading
RUN composer require hirak/prestissimo

# fix permissions for files mounted to host - change container's user ID to your host's user ID
# be sure, that your local user has id=1000
RUN usermod -u 1000 www-data

USER www-data

WORKDIR /app