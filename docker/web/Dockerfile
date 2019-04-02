FROM php:7.1.27-apache

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update
RUN apt-get install -y git

RUN apt-get install -y locales \
    && echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
    && locale-gen

RUN apt-get install -y apt-transport-https \
    libssl-dev \
    gnupg

# https://docs.microsoft.com/en-us/sql/connect/odbc/linux-mac/installing-the-microsoft-odbc-driver-for-sql-server?view=sql-server-2017
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && export $(grep VERSION_ID /etc/os-release | sed -e 's/"//g') \
    && curl https://packages.microsoft.com/config/debian/${VERSION_ID}/prod.list > /etc/apt/sources.list.d/mssql-release.list

RUN apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql17 mssql-tools \
    && apt-get install -y unixodbc-dev \
    && pecl install sqlsrv \
    && docker-php-ext-enable sqlsrv \
    && pecl install pdo_sqlsrv \
    && docker-php-ext-enable pdo_sqlsrv

RUN apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    libicu-dev \
    libxml2-dev \
    libxslt1-dev \
    libtidy-dev \
    && docker-php-ext-install -j$(nproc) iconv mcrypt opcache pdo_mysql \
    && docker-php-ext-install -j$(nproc) bcmath calendar exif gettext intl mysqli soap tidy wddx xmlrpc xsl zip \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

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
