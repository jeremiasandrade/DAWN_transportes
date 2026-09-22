FROM php:8.4-apache

# Actualiza e instala dependencias del sistema operativo
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Instala extensiones de PHP necesarias para Laravel y MariaDB
RUN docker-php-ext-install pdo_mysql mbstring

# Configura la carpeta public/ de Laravel como la raíz de Apache
ENV APACHE_ROOT_DIR /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_ROOT_DIR}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_ROOT_DIR}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Habilita el módulo de reescritura para las rutas
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copia los archivos del proyecto al contenedor
COPY . .

# Asigna los permisos adecuados para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
