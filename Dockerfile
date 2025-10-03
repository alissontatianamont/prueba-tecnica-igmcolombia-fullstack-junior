FROM php:8.2-apache

# Actualizar repositorios e instalar dependencias básicas
RUN apt-get update -y

# Instalar dependencias del sistema
RUN apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Instalar Node.js 18 LTS
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Instalar extensiones PHP para MySQL y SQLite
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Habilitar módulos Apache
RUN a2enmod rewrite

# Limpiar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar Apache para SPA + API
COPY docker/apache-spa.conf /etc/apache2/sites-available/000-default.conf

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias de Composer (Laravel API)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Instalar dependencias de Node.js y compilar Vue.js SPA
RUN npm ci && npm run build

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