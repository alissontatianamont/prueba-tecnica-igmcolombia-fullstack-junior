# Sistema de Facturación IGM Colombia

Bienvenidos a esta prueba técnica, un sistema de facturación desarrollado en Laravel 11, ccompuesto por diferentes módulos que entre sí se complementan, a continuación listamos los requisitos tecnicos y caracteristicas principaes.

## Características Técnicas Principales

- **Autenticación y Autorización**: Sanctum + Spatie Permissions
- **Gestión de Roles**: Admin (acceso completo) y Salesman (acceso limitado)
- **CRUD Completo**: Usuarios, Clientes, Productos y Facturas
- **Generación Automática de PDFs**: Con plantillas personalizadas (se genera al crear o actualizar una factura)
- **Almacenamiento en AWS S3**: Archivos PDF encriptados y seguros mediante bucket privado
- **Protección de Datos**: Restricciones de eliminación para mantener integridad de los datos del cliente

## Configuración Rápida

### Requisitos Previos
- PHP 8.2+
- Composer
- MySQL/MariaDB 8

### 1. Instalación
```bash
# Clonar e instalar dependencias
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Configurar Variables de Entorno
```env
# Base de datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=igm_facturacion
DB_USERNAME=usuario_servidorBD
DB_PASSWORD=contraseña_servidorBD

# AWS S3 (opcional para PDFs)
AWS_ACCESS_KEY_ID=access_key
AWS_SECRET_ACCESS_KEY=secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=bucket_name

# Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1
```

### 3. Configuración Automática
```bash
# Configuración completa (este comando genera ejecución de migraciones y seeders )
php artisan app:setup --fresh

# Solo agregar datos (Migraciones ya ejecutadas)
php artisan app:setup

## Estructura de Base de Datos

### Migraciones Optimizadas
El sistema utiliza solo migraciones `create_` principales con todos los campos necesarios:

- **`create_users_table`**: Usuarios con soporte para Sanctum
- **`create_clients_table`**: Clientes con estado (cli_status) incluido
- **`create_products_table`**: Productos con precios e IVA
- **`create_invoices_table`**: Facturas con IVA (inv_iva_percentage) incluido
- **`create_invoice_items_table`**: Items de facturas
- **`create_invoice_files_table`**: Archivos PDF con campos de encriptación incluidos
- **`create_permission_tables`**: Spatie permissions
- **`create_personal_access_tokens_table`**: Sanctum tokens

### Comando de Migración
```bash
# Crear BD desde cero
php artisan migrate:fresh --seed

# Solo migraciones
php artisan migrate
```

##  Usuarios por Defecto (SE GENERAN CON LOS RESPECTIVOS SEEDERS)

|    Usuario   |         Email        |     Password     |    Rol   |  Acceso  |
|--------------|----------------------|------------------|----------|----------|
|   **Admin**  |  admin@example.com   | passwordadmin123 |  admin   | Completo |
| **Vendedor** | salesman@example.com | passwordsales123 | salesman | Limitado |

## Módulos del Sistema

### Autenticación
- Login con email/password retorna token Sanctum
- Middleware de permisos por endpoint
- Logout invalida token activo

### Usuarios (SOLO ROL ADMIN)
- CRUD completo con asignación de roles
- Restricción: No se puede eliminar usuarios con facturas
- Permisos: create, view, edit, delete users

### Clientes
- CRUD completo con sistema de estados (activo/inactivo)
- Tipos de documento: CC, CE, TI, NIT
- **Protección de datos**: Clientes con facturas se desactivan en lugar de eliminarse
- Endpoints adicionales: activate/deactivate
- Filtrado por estado: `?status=active|inactive`

### Productos
- CRUD completo con control de stock e IVA
- Estados: active/inactive
- Cálculo automático de precios en facturas
- Admin: acceso completo / Salesman: solo lectura

### Facturas
- Creación con items y cálculo automático de totales
- Numeración correlativa automática (FACT-XXX...)
- **Generación automática de PDF** al crear/actualizar
- **Almacenamiento seguro en S3** con URLs encriptadas
- Edición permitida solo antes de fecha de vencimiento

## API Endpoints

### Autenticación
```bash
POST /api/v1/auth/login     # { "email": "admin@example.com", "password": "passwordadmin123" }
POST /api/v1/auth/logout    # Header: Authorization: Bearer {token}
```

### Usuarios (Requiere rol admin)
```bash
GET    /api/v1/users           # Listar con filtros y paginación
POST   /api/v1/users           # Crear + asignar rol
GET    /api/v1/users/{id}      # Ver detalles
PATCH  /api/v1/users/{id}      # Actualizar
DELETE /api/v1/users/{id}      # Eliminar (bloqueado si tiene facturas)
```

**Filtros disponibles:**
- `name`: Buscar por nombre (LIKE)
- `email`: Buscar por email (LIKE)
- `role`: Filtrar por rol (admin/salesman)
- `sort_by`: Ordenar por (name, email, created_at)
- `sort_direction`: Dirección (asc/desc)
- `per_page`: Elementos por página (default: 15)
- `paginate=false`: Devolver todos sin paginación

### Clientes
```bash
GET    /api/v1/clients                    # Listar con filtros y paginación
POST   /api/v1/clients                    # Crear (cli_status=1 por defecto)
GET    /api/v1/clients/{id}               # Ver
PATCH  /api/v1/clients/{id}               # Actualizar
DELETE /api/v1/clients/{id}               # Eliminar/desactivar si tiene facturas
PATCH  /api/v1/clients/{id}/activate      # Activar manualmente
PATCH  /api/v1/clients/{id}/deactivate    # Desactivar manualmente
```

**Filtros disponibles:**
- `name`: Buscar en nombres (LIKE en todos los campos de nombre)
- `document`: Buscar por número de documento (LIKE)
- `email`: Buscar por email (LIKE)
- `phone`: Buscar por teléfono (LIKE)
- `document_type`: Filtrar por tipo (CC, CE, TI, NIT)
- `status`: Filtrar por estado (1=activo, 2=inactivo)
- `sort_by`: Ordenar por (cli_first_name, cli_last_name, cli_email, cli_document_number, created_at)
- `sort_direction`: Dirección (asc/desc)
- `per_page`: Elementos por página (default: 15)
- `paginate=false`: Devolver todos sin paginación

### Productos
```bash
GET    /api/v1/products       # Listar
POST   /api/v1/products       # Crear (solo admin)
GET    /api/v1/products/{id}  # Ver
PATCH  /api/v1/products/{id}  # Actualizar (solo admin)
DELETE /api/v1/products/{id}  # Eliminar (solo admin)
```

### Facturas
```bash
GET    /api/v1/invoices              # Listar con filtros avanzados y paginación
POST   /api/v1/invoices              # Crear (genera PDF automáticamente)
GET    /api/v1/invoices/{id}         # Ver con items
PATCH  /api/v1/invoices/{id}         # Actualizar (regenera PDF)
DELETE /api/v1/invoices/{id}         # Eliminar (limpia archivos S3)
GET    /api/v1/invoices/{id}/file    # URL temporal del PDF
```

**Filtros disponibles:**
- `inv_number`: Buscar por número de factura (LIKE)
- `client_name`: Buscar por nombre del cliente (LIKE)
- `client_document`: Buscar por documento del cliente (LIKE)
- `status`: Filtrar por estado (pending, paid, overdue)
- `date_from`: Fecha de emisión desde (Y-m-d)
- `date_to`: Fecha de emisión hasta (Y-m-d)
- `due_date_from`: Fecha de vencimiento desde (Y-m-d)
- `due_date_to`: Fecha de vencimiento hasta (Y-m-d)
- `sort_by`: Ordenar por (created_at, inv_issue_date, inv_due_date, inv_number, inv_total_amount)
- `sort_direction`: Dirección (asc/desc)
- `per_page`: Elementos por página (default: 15)
- `paginate=false`: Devolver todos sin paginación

### Archivos
```bash
GET /files/serve/{encryptedPath}     # Servir PDF con URL encriptada (sin auth)
```

## 📝 Ejemplos de Filtros y Paginación

### Facturas con Filtros
```bash
# Buscar facturas por número
GET /api/v1/invoices?inv_number=FACT-001

# Filtrar por cliente y fechas
GET /api/v1/invoices?client_name=Juan&date_from=2025-01-01&date_to=2025-12-31

# Ordenar por fecha de vencimiento descendente
GET /api/v1/invoices?sort_by=inv_due_date&sort_direction=desc

# Paginación personalizada
GET /api/v1/invoices?per_page=25&page=2

# Facturas vencidas
GET /api/v1/invoices?status=overdue&sort_by=inv_due_date
```

### Clientes con Filtros
```bash
# Buscar por nombre
GET /api/v1/clients?name=María

# Filtrar por tipo de documento
GET /api/v1/clients?document_type=CC

# Solo clientes activos
GET /api/v1/clients?status=1

# Buscar por documento y ordenar por nombre
GET /api/v1/clients?document=12345&sort_by=cli_first_name&sort_direction=asc
```

### Usuarios con Filtros
```bash
# Buscar por email
GET /api/v1/users?email=admin

# Solo administradores
GET /api/v1/users?role=admin

# Sin paginación
GET /api/v1/users?paginate=false
```

### Estructura de Respuesta Paginada
```json
{
  "data": [...],
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 67,
    "from": 1,
    "to": 15
  },
  "filters": {
    "name": "juan",
    "sort_by": "created_at"
  },
  "message": "Data retrieved successfully"
}
```

## Sistema de Permisos

### Roles y Permisos
```bash
# Admin (acceso completo)
Users: create, view, edit, delete
Clients: create, view, edit, delete  
Products: create, view, edit, delete
Invoices: create, view, edit, delete

# Salesman (limitado)
Clients: create, view, edit
Products: view
Invoices: create, view, edit
```

### Protección de Datos

#### Usuarios
- **Restricción**: No se pueden eliminar usuarios con facturas asociadas
- **Respuesta**: Error 422 con mensaje explicativo
- **Solución**: Transferir facturas a otro usuario antes de eliminar

#### Clientes
- **Comportamiento**: Clientes con facturas se desactivan (`cli_status=2`) en lugar de eliminarse
- **Respuesta**: Error 422 explicando la desactivación
- **Beneficio**: Mantiene integridad de datos históricos
- **Gestión**: Endpoints de activate/deactivate para control manual

## Generación de PDFs

### Proceso Automático
1. **Generación**: PDF con datos completos (cliente, items, totales)
2. **Almacenamiento**: S3 con ruta encriptada
3. **Acceso**: URL temporal con expiración

### Plantilla PDF
- Logo y datos de empresa
- Información completa del cliente
- Desglose de productos con precios e IVA
- Totales calculados automáticamente
- Número de factura y fechas

### Gestión de Archivos
- **Limpieza automática BUCKET S3**: Al eliminar facturas se borran PDFs de S3
- **URLs seguras**: Rutas encriptadas para prevenir acceso directo
- **Expiración**: Enlaces temporales con tiempo límite

## 🛠️ Comandos Útiles

### Configuración
```bash
# Configuración inicial completa
php artisan app:setup --fresh

# Solo seeders (preserva datos)
php artisan app:setup

# Resetear permisos
php artisan permission:cache-reset
```


## Desgloce Datos de Ejemplo

## El seeder crea automáticamente:
- **2 usuarios**: Admin completo y Vendedor limitado
- **6 clientes**: Personas naturales y jurídicas con datos colombianos
- **10 productos**: Productos tecnológicos con precios realistas
- **16 permisos**: Distribuidos correctamente entre roles
