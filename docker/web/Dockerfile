FROM php:8.0-apache

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && \
    apt-get install -y \
    git \
    ssh \
    tar \
    gzip \
    ca-certificates \
    zip unzip \
    gnupg

RUN apt-get install -y locales \
    && echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
    && locale-gen

# https://docs.microsoft.com/en-us/sql/connect/odbc/linux-mac/installing-the-microsoft-odbc-driver-for-sql-server?view=sql-server-2017
RUN apt-get update && apt-get install -y \
    apt-transport-https \
    && curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && export $(grep VERSION_ID /etc/os-release | sed -e 's/"//g') \
    && curl https://packages.microsoft.com/config/debian/${VERSION_ID}/prod.list > /etc/apt/sources.list.d/mssql-release.list

RUN apt-get update && ACCEPT_EULA=Y apt-get install -y \
    unixodbc \
    unixodbc-dev \
    mssql-tools \
    libgss3 \
    odbcinst \
    msodbcsql17

RUN pecl install pdo_sqlsrv sqlsrv \
    && docker-php-ext-enable pdo_sqlsrv sqlsrv

RUN apt-get update \
    # pdo_mysql
    && docker-php-ext-install pdo_mysql \
    # opcache
    && docker-php-ext-enable opcache \
    # clean up
    && apt-get autoclean -y \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/pear/

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install ast \
    && docker-php-ext-enable ast

RUN a2enmod rewrite \
    && a2enmod headers

COPY sites-available/app.conf /etc/apache2/sites-available/
RUN rm /etc/apache2/sites-enabled/000-default.conf \
    && ln -s /etc/apache2/sites-available/app.conf /etc/apache2/sites-enabled/app.conf

COPY config/php.ini /usr/local/etc/php/

RUN php -r "copy('https://raw.githubusercontent.com/composer/getcomposer.org/master/web/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

ENV PATH /var/www/html/vendor/bin:$PATH

EXPOSE 80
