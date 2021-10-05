FROM php:7.3-fpm

RUN apt-get update \
&& docker-php-ext-install pdo pdo_mysql \
&& curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=1.10.16 \
&& apt-get install -y --no-install-recommends --fix-missing\
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libxpm-dev \
    libvpx-dev \
    libwebp-dev \
&& docker-php-ext-configure gd \
    --with-freetype-dir=/usr/lib/x86_64-linux-gnu/ \
    --with-jpeg-dir=/usr/lib/x86_64-linux-gnu/ \
    --with-xpm-dir=/usr/lib/x86_64-linux-gnu/ \
    --with-webp-dir=/usr/lib/x86_64-linux-gnu/ \
&& docker-php-ext-install gd \ 
&& docker-php-ext-install exif

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
--with-webp-dir=/usr/include
RUN docker-php-ext-install -j$(nproc) gd