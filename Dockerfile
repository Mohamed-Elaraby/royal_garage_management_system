# Isolated legacy PHP runtime for this Laravel 5.8 project.
# Composer 2.2 is the LTS line that remains compatible with PHP 7.4.
FROM php:7.4-cli-bullseye

# Bullseye is archived upstream. Use its immutable archive so rebuilding this
# legacy runtime does not fail when old security package revisions disappear.
RUN sed -i \
        -e '/bullseye-updates/d' \
        -e 's|deb.debian.org/debian|archive.debian.org/debian|g' \
        /etc/apt/sources.list \
    && apt-get -o Acquire::Check-Valid-Until=false update \
    && apt-get install -y --no-install-recommends \
        git \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libmagickwand-dev \
        libonig-dev \
        libpng-dev \
        libsqlite3-dev \
        libxml2-dev \
        libzip-dev \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        gd \
        intl \
        mbstring \
        pdo_mysql \
        pdo_sqlite \
        soap \
        zip \
    && pecl install https://pecl.php.net/get/imagick-3.7.0.tgz \
        && docker-php-ext-enable imagick \
    && rm -rf /var/lib/apt/lists/* /tmp/pear

COPY --from=composer:2.2 /usr/bin/composer /usr/local/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_HOME=/tmp/composer

WORKDIR /var/www/html
