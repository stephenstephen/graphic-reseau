FROM php:7.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    libxslt1-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libsodium-dev \
    unzip \
    vim \
    wget \
    cron \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure GD library
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Install PHP extensions required by Magento 2.4.2
RUN docker-php-ext-install -j$(nproc) \
    bcmath \
    ctype \
    curl \
    dom \
    fileinfo \
    gd \
    hash \
    iconv \
    intl \
    mbstring \
    pdo_mysql \
    simplexml \
    soap \
    sockets \
    sodium \
    tokenizer \
    xmlwriter \
    xsl \
    zip

# Install Composer 2.x
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy custom PHP configuration
COPY docker/php/php.ini /usr/local/etc/php/conf.d/magento.ini
COPY docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/zz-magento.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 9000 for PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
