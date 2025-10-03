#!/bin/bash
set -e

echo "🚀 === INICIANDO DEPLOY LARAVEL API + VUE SPA + MYSQL EXTERNO ==="

# 1. CONFIGURAR LARAVEL API
echo "⚙️  Configurando Laravel API con MySQL externo..."

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

# 2. EJECUTAR MIGRACIONES Y SEEDERS
echo "🗄️  Ejecutando migraciones MySQL..."
php artisan migrate --force

echo "🌱 Ejecutando seeders..."
php artisan db:seed --force

# 3. OPTIMIZAR LARAVEL PARA PRODUCCIÓN
echo "🚀 Optimizando Laravel para producción..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Crear enlace simbólico para storage
php artisan storage:link

# 4. VERIFICAR QUE VUE.js SPA ESTÉ COMPILADO
echo "🎨 Verificando compilación de Vue.js SPA..."
if [ ! -d "public/build" ]; then
    echo "❌ Error: Vue.js no está compilado. Ejecutando npm run build..."
    npm run build
fi

echo "✅ Frontend Vue.js SPA listo"

# 5. CONFIGURAR PERMISOS FINALES
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html/storage
chmod -R 755 /var/www/html/bootstrap/cache

echo "🌐 === INICIANDO SERVIDOR APACHE ==="
echo "📡 API Laravel disponible en: /api/*"
echo "🎨 SPA Vue.js disponible en: /*"
echo "🗄️  Base de datos MySQL externa conectada"

# Iniciar Apache en foreground
exec apache2-foreground