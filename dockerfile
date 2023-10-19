# # FROM php:8.1.22-fpm-alpine

# # RUN apk add --no-cache freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev && \
# #   docker-php-ext-configure gd \
# #     --with-freetype \
# #     --with-jpeg \
# #  NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
# #   docker-php-ext-install -j$(nproc) gd && \
# #   apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev

# # RUN mkdir -p /run/nginx

# # COPY docker/nginx.conf /etc/nginx/nginx.conf

# # RUN mkdir -p /app
# # COPY . /app

# # # Install Composer
# # RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# # # Set the working directory
# # WORKDIR /app

# # # Run Composer
# # RUN /usr/local/bin/composer install --no-scripts

# # RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
# # RUN mkdir -p /app && cd /app && /usr/local/bin/composer install --no-scripts


# # RUN chown -R www-data: /app

# # CMD sh /app/docker/startup.sh


# # PHP Dependencies
# # FROM composer:2.1.6 as vendor
# # RUN apk add --no-cache freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev && \
# #   docker-php-ext-configure gd \
# #     --with-freetype \
# #     --with-jpeg \
# #   NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
# #   docker-php-ext-install -j$(nproc) gd && \
# #   apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev

# # COPY . /app
# # RUN composer install \
# #     --no-scripts
# # RUN composer dump-autoload


# FROM php:8.1.22-fpm-alpine

# RUN apk add --no-cache freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev && \
#     docker-php-ext-configure gd --with-freetype --with-jpeg && \
#     docker-php-ext-install -j$(nproc) gd && \
#     apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev

# RUN mkdir -p /run/nginx
# COPY docker/nginx.conf /etc/nginx/nginx.conf

# RUN mkdir -p /app
# WORKDIR /app
# COPY . /app

# # Install Composer
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# # Run Composer to install dependencies
# RUN /usr/local/bin/composer install --no-scripts

# # Change ownership to www-data
# RUN chown -R www-data: /app

# CMD sh /app/docker/startup.sh



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
