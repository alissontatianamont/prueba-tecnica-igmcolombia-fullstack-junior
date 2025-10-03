#!/bin/bash
set -e

echo "🚀 === INICIANDO DEPLOY LARAVEL API + VUE SPA + MYSQL ==="

# 1. CONFIGURAR MYSQL
echo "📦 Configurando MySQL 8..."
service mysql start

# Configurar MySQL root sin password para container
mysql -u root -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '';"
mysql -u root -e "FLUSH PRIVILEGES;"

# Crear base de datos y usuario para Laravel
mysql -u root -e "CREATE DATABASE IF NOT EXISTS igm_facturacion_db;"
mysql -u root -e "CREATE USER IF NOT EXISTS 'laravel_user'@'%' IDENTIFIED BY 'laravel_pass_2024';"
mysql -u root -e "GRANT ALL PRIVILEGES ON igm_facturacion_db.* TO 'laravel_user'@'%';"
mysql -u root -e "FLUSH PRIVILEGES;"

echo "✅ MySQL configurado correctamente"

# 2. CONFIGURAR VARIABLES DE ENTORNO LARAVEL
export DB_CONNECTION=mysql
export DB_HOST=127.0.0.1
export DB_PORT=3306
export DB_DATABASE=igm_facturacion_db
export DB_USERNAME=laravel_user
export DB_PASSWORD=laravel_pass_2024

# 3. CONFIGURAR LARAVEL API
echo "⚙️  Configurando Laravel API..."

# Generar APP_KEY si no existe
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generando APP_KEY..."
    php artisan key:generate --force
fi

# Limpiar cache Laravel
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 4. EJECUTAR MIGRACIONES Y SEEDERS
echo "🗄️  Ejecutando migraciones MySQL..."
php artisan migrate --force

echo "🌱 Ejecutando seeders..."
php artisan db:seed --force

# 5. OPTIMIZAR LARAVEL PARA PRODUCCIÓN
echo "🚀 Optimizando Laravel para producción..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Crear enlace simbólico para storage
php artisan storage:link

# 6. VERIFICAR QUE VUE.js SPA ESTÉ COMPILADO
echo "🎨 Verificando compilación de Vue.js SPA..."
if [ ! -d "public/build" ]; then
    echo "❌ Error: Vue.js no está compilado. Ejecutando npm run build..."
    npm run build
fi

echo "✅ Frontend Vue.js SPA listo"

# 7. CONFIGURAR PERMISOS FINALES
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html/storage
chmod -R 755 /var/www/html/bootstrap/cache

echo "🌐 === INICIANDO SERVIDOR APACHE ==="
echo "📡 API Laravel disponible en: /api/*"
echo "🎨 SPA Vue.js disponible en: /*"
echo "🗄️  Base de datos MySQL: igm_facturacion_db"

# Iniciar Apache en foreground
exec apache2-foreground