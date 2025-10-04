#!/bin/bash
set -e

echo "🚀 === INICIA# 5. OPTIMIZAR LARAVEL PARA PRODUCCIÓN
echo "🚀 Optimizando Laravel para producción..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Crear enlace simbólico para storage
if [ ! -L "public/storage" ]; then
    php artisan storage:link
fiOY LARAVEL API + VUE SPA + MYSQL EXTERNO ==="

# 1. CONFIGURAR LARAVEL API
echo "⚙️  Configurando Laravel API con MySQL externo..."

# Generar APP_KEY si no existe
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generando APP_KEY..."
    php artisan key:generate --force
fi

# Configurar cache temporal para evitar errores de tabla no encontrada
export CACHE_STORE=file
export SESSION_DRIVER=file

# Configurar URL de API para Vite (debe coincidir con Render)
export VITE_API_BASE_URL="${APP_URL}/api/v1"

# Limpiar configuraciones
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 2. EJECUTAR MIGRACIONES Y SEEDERS
echo "🗄️  Ejecutando migraciones MySQL..."
php artisan migrate --force

echo "🌱 Ejecutando seeders..."
php artisan db:seed --force

# 3. RESTAURAR CONFIGURACIÓN DE PRODUCCIÓN
echo "⚙️  Restaurando configuración de cache de producción..."
# Solo si las variables están configuradas para usar database cache
if [ "$CACHE_STORE" = "database" ]; then
    echo "🗄️  Configurando cache de base de datos..."
    # Ahora sí podemos usar cache de base de datos porque las tablas existen
    php artisan cache:clear
fi

# 4. OPTIMIZAR LARAVEL PARA PRODUCCIÓN
echo "🚀 Optimizando Laravel para producción..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Crear enlace simbólico para storage
php artisan storage:link

# 4. VERIFICAR QUE VUE.js SPA ESTÉ COMPILADO
echo "🎨 Verificando compilación de Vue.js SPA..."

# Listar contenido de public/build para debug
echo "📁 Contenido de public/build:"
ls -la public/build/ || echo "❌ Directorio public/build no existe"

# Verificar que el manifest existe
if [ ! -f "public/build/manifest.json" ]; then
    echo "❌ Error: Manifest no encontrado. Los archivos compilados no están presentes."
    echo "📁 Listando archivos en public:"
    ls -la public/
    echo "❌ El build de Vue.js falló durante la construcción del Docker."
    exit 1
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