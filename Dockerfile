FROM php:8.3-fpm-alpine

# Instal·lar dependències del sistema
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    postgresql-dev \
    oniguruma-dev

# Instal·lar extensions de PHP
RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring zip exif pcntl bcmath gd

# Instal·lar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear usuari per Laravel
RUN addgroup -g 1000 www && adduser -D -u 1000 -G www www

# Establir directori de treball
WORKDIR /var/www

# Canviar a l'usuari www
USER www

# Exposar port 9000 i iniciar php-fpm
EXPOSE 9000
CMD ["php-fpm"]
