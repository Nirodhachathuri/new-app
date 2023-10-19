FROM php:8.1.22-fpm-alpine

RUN apk add --no-cache freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd && \
    apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev

RUN apk add --no-cache libzip-dev

RUN apk add --no-cache nginx

RUN mkdir -p /run/nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

RUN mkdir -p /app
WORKDIR /app
COPY . /app




# Enable the ext-zip extension
RUN docker-php-ext-install zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Run Composer to install dependencies
RUN COMPOSER_ALLOW_SUPERUSER=1 /usr/local/bin/composer install --no-scripts

# Change ownership to www-data
RUN chown -R www-data: /app

CMD sh /app/docker/startup.sh
