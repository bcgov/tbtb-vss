FROM php:8.1-apache
ARG DEBIAN_VERSION=20.04
ARG APACHE_OPENIDC_VERSION=2.4.10
ARG USER_ID=1009370000
ARG TZ=America/Vancouver
ARG CA_HOSTS_LIST
ARG TEST_ARG
ARG ENV_ARG

ARG DEBIAN_FRONTEND=noninteractive

WORKDIR /

RUN apt-get -y update --fix-missing
RUN apt-get update && apt-get install -y --no-install-recommends apt-utils
#php setup, install extensions, setup configs
RUN apt-get update && apt-get install --no-install-recommends -y \
    libzip-dev \
    libxml2-dev \
    zip \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN pecl install zip pcov
RUN docker-php-ext-enable zip \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install soap \
    && docker-php-source delete

#disable exposing server information
RUN sed -ri -e 's!expose_php = On!expose_php = Off!g' $PHP_INI_DIR/php.ini-production \
    && sed -ri -e 's!ServerTokens OS!ServerTokens Prod!g' /etc/apache2/conf-available/security.conf \
    && sed -ri -e 's!ServerSignature On!ServerSignature Off!g' /etc/apache2/conf-available/security.conf \
    && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN apt-get update -qq \
    && apt-get install -yq apt-utils zlib1g-dev g++ libicu-dev unzip libzip-dev zip libpq-dev git nano netcat curl apache2 dialog locate libcurl4 libcurl3-dev psmisc \
	libfreetype6-dev \
    libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        libmcrypt-dev \
        libpng-dev \

    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-install intl opcache\
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

# Install Postgre PDO
RUN apt-get install -y libpq-dev libonig-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

RUN docker-php-ext-install curl
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd

RUN a2enmod rewrite



ENV APACHE_REMOTE_IP_HEADER=X-Forwarded-For
ENV APACHE_REMOTE_IP_TRUSTED_PROXY="10.0.0.0/8 172.16.0.0/12 192.168.0.0/16 10.97.6.0/16 10.97.6.1"
ENV APACHE_REMOTE_IP_INTERNAL_PROXY="10.0.0.0/8 172.16.0.0/12 192.168.0.0/16 10.97.6.0/16 10.97.6.1"

# Apache - disable Etag
RUN a2enmod remoteip \
    && a2enmod rewrite \
    && a2enmod auth_basic \
    && a2enmod authn_file \
    && a2enmod authz_user \
    && a2enmod autoindex \
    && a2enmod deflate \
    && a2enmod filter \
    && a2dismod mpm_event && a2dismod  mpm_worker && a2enmod mpm_prefork \
    && a2enmod reqtimeout \
    && a2enmod setenvif \
    && sed -i 's/%h/%a/g' /etc/apache2/apache2.conf

RUN { \
        echo 'RemoteIPHeader X-Forwarded-For'; \
        echo 'RemoteIPInternalProxy 10.0.0.0/8'; \
        echo 'RemoteIPInternalProxy 172.16.0.0/12'; \
        echo 'RemoteIPInternalProxy 192.168.0.0/16'; \
        echo 'RemoteIPInternalProxy 169.254.0.0/16'; \
        echo 'RemoteIPInternalProxy 127.0.0.0/8'; \
    } | tee "$APACHE_CONFDIR/conf-available/remoteip.conf" && \
    a2enconf remoteip

# Apache - Hide version
RUN sed -i -e 's/^ServerTokens OS$/ServerTokens Prod/g' \
        -e 's/^ServerSignature On$/ServerSignature Off/g' \
        /etc/apache2/conf-available/security.conf

# Enable apache modules
RUN a2enmod rewrite headers

# Install NPM
RUN curl --silent --location https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs

# Install Yarn
RUN curl -sL https://dl.yarnpkg.com/debian/pubkey.gpg | gpg --dearmor | tee /usr/share/keyrings/yarnkey.gpg >/dev/null
RUN echo "deb [signed-by=/usr/share/keyrings/yarnkey.gpg] https://dl.yarnpkg.com/debian stable main" | tee /etc/apt/sources.list.d/yarn.list


RUN npm config list
RUN npm config ls -l

RUN apt-get autoclean
RUN apt-get autoremove

#fix Action '-D FOREGROUND' failed.
RUN a2enmod lbmethod_byrequests

RUN echo ${TEST_ARG}

# System - Set default timezone
ENV TZ=${TZ}


RUN mkdir -p /var/log/php
RUN printf 'error_log=/var/log/php/error.log\nlog_errors=1\nerror_reporting=E_ALL\n' > /usr/local/etc/php/conf.d/custom.ini

# Composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer

WORKDIR /
COPY openshift/apache-oc/image-files/ /
RUN mkdir -p /etc/apache2/sites-enabled
COPY openshift/apache-oc/image-files/etc/apache2/sites-available/000-default.conf /etc/apache2/sites-enabled/000-default.conf

ENV APACHE_SERVER_NAME=__default__

EXPOSE 8080 8443 2525
RUN sed -i -e 's/80/8080/g' -e 's/443/8443/g' -e 's/25/2525/g' /etc/apache2/ports.conf \
    # Apache- Prepare to be run as non root user
    && mkdir -p /var/lock/apache2 /var/run/apache2 \
    && chgrp -R 0 /etc/apache2/mods-* \
        /etc/apache2/sites-* \
        /run /var/lib/apache2 \
        /var/run/apache2 \
        /var/lock/apache2 \
        /var/log/apache2 \
    && chmod -R g=u /etc/passwd \
        /etc/apache2/mods-* \
        /etc/apache2/sites-* \
        /run \
        /var/lib/apache2 \
        /var/run/apache2 \
        /var/lock/apache2 \
        /var/log/apache2 \
    # Apache - Display information (version, module)
    && a2query -v \
    && a2query -M \
    && a2query -m \
    && chmod a+rx /docker-bin/*.sh \
    && /docker-bin/docker-build.sh

RUN export COMPOSER_HOME="$HOME/.config/composer";

COPY entrypoint.sh /sbin/entrypoint.sh


# set entrypoint variables
ENV USER_NAME=${USER_ID}
ENV USER_HOME=/var/www/html

COPY / /var/www/html/

WORKDIR /var/www/html/

RUN touch .env && echo ${ENV_ARG} >> /var/www/html/.env

RUN mkdir -p storage && mkdir -p bootstrap/cache &&chmod -R ug+rwx storage bootstrap/cache
RUN cd /var/www && chown -R ${USER_ID}:root html && chmod -R ug+rw html

RUN cd ~ && chown -R ${USER_ID}:root .npm && chmod -R 766 .npm


RUN npm cache clean --force
RUN npm cache verify

RUN chmod 764 /var/www/html/artisan

#now install npm
RUN cd /var/www/html && npm install
RUN cd /var/www/html && chmod -R a+w node_modules

#Error: EACCES: permission denied, open '/var/www/html/public/mix-manifest.json'
RUN cd /var/www/html/public && chmod 766 mix-manifest.json

#Writing to directory /.config/psysh is not allowed.
RUN mkdir -p /.config/psysh
RUN chown -R ${USER_ID}:root /.config && chmod -R 755 /.config
RUN echo "<?php return ['runtimeDir' => '/tmp'];" >> /.config/psysh/config.php

#openshift will complaine about permission
#RUN chmod +x /sbin/entrypoint.sh
RUN ["chmod", "+x", "/sbin/entrypoint.sh"]
USER ${USER_ID}

#ENTRYPOINT ["/sbin/entrypoint.sh"]
#CMD /usr/sbin/apache2ctl start && /usr/sbin/apache2ctl restart && /sbin/entrypoint.sh

# Apply necessary permissions
ENTRYPOINT ["/sbin/entrypoint.sh"]

CMD ["apache2-foreground"]
