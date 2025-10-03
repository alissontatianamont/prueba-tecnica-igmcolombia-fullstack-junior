FROM php:8.2-apache

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    mysql-server \
    mysql-client \
    supervisor \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && a2enmod rewrite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar Apache para SPA + API
COPY docker/apache-spa.conf /etc/apache2/sites-available/000-default.conf

# Configurar Supervisor para MySQL + Apache
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Configurar MySQL
RUN mkdir -p /var/run/mysqld /var/log/mysql \
    && chown mysql:mysql /var/run/mysqld /var/log/mysql

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias de Composer (Laravel API)
RUN composer install --no-dev --optimize-autoloader

# Instalar dependencias de Node.js y compilar Vue.js SPA
RUN npm install && npm run build

# Configurar permisos Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache \
    && chmod -R 755 /var/www/html/public

# Script de inicio personalizado
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Exponer puerto 80
EXPOSE 80

# Comando de inicio
CMD ["/entrypoint.sh"]