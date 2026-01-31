# 🚀 MANUAL DE INSTALACIÓN - CLUBPENSION CRM

## 1. PREREQUISITOS

### Software requerido:
- ✅ XAMPP (Apache + PHP)
- ✅ PHP 8.2 o superior
- ✅ mySQL Workbench 8.0
- ✅ Composer 2.5+
- ✅ Git (opcional)
- ✅ Editor de código (VS Code, PHPStorm, etc.)

### Verificar instalaciones:
powershell
```bash
# Verificar PHP
php --version
# Debe mostrar PHP 8.2+

# Verificar Composer
composer --version
# Debe mostrar Composer 2.5+

# Verificar MySQL
mysql --version





🔄 PASO 1: CREAR NUEVO PROYECTO LARAVEL
powershell
# 1. Crear proyecto
cd C:\xampp\htdocs
composer create-project laravel/laravel clubpension "10.*"

# 2. Navegar al proyecto
cd clubpension

# 3. Verificar instalación
php artisan --version

================================================================
⚙️ PASO 2: CONFIGURACIÓN BÁSICA
powershell
# 1. Generar APP_KEY (el archivo .env ya viene con Laravel)
php artisan key:generate

# 2. Configurar .env (EDITAR MANUALMENTE CON ESTOS VALORES)
APP_NAME=ClubPension
APP_ENV=local
APP_KEY=base64:xVfv429GKuyI6ZQ+mlAVCVzUJCNyNVyKRnmXyhoFtq0=
APP_DEBUG=true
APP_URL=http://localhost/clubpension/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clubpension
DB_USERNAME=root
DB_PASSWORD=Rynd3w23

BROADCAST_DRIVER=log
CACHE_DRIVER=array
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# 3. Crear base de datos en MySQL Workbench
# Manualmente: CREATE DATABASE clubpension;
Nombre: clubpension
Collation: utf8mb4_unicode_ci
Ejecutar schema.sql

sql
SELECT * FROM usuarios WHERE email = 'dronces@hotmail.com';
Si no existe, créalo:

sql
INSERT INTO usuarios (username, email, password_hash, nombre, apellidos, rol, activo) 
VALUES ('admin', 'dronces@hotmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'Sistema', 'admin', 1);
================================================================

📦 PASO 3: INSTALAR PAQUETES ESENCIALES
powershell
# 1. Spatie Laravel Permission
composer require spatie/laravel-permission

# 2. Laravel UI (para autenticación)
composer require laravel/ui

# 3. Instalar Bootstrap
php artisan ui bootstrap

# 4. Laravel Collective HTML (para forms)
composer require laravelcollective/html

# 5. Publicar configuración de Spatie
#php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"

================================================================
👤 PASO 4: CONFIGURAR SISTEMA DE USUARIOS PERSONALIZADO

powershell
# 1. Crear modelo Usuario personalizado
php artisan make:model Usuario

4.1 Crear modelo Usuario personalizado
C:\xampp\htdocs\clubpension\app\Models\Usuario.php

4.2 Configurar auth.php
C:\xampp\htdocs\clubpension\config\auth.php

C:\xampp\htdocs\clubpension\config\permission.php
================================================================
📁 PASO 5: CREA ESTAS CARPETAS:
bash
# En PowerShell o desde el explorador de archivos

# 1. Ir al proyecto
cd C:\xampp\htdocs\clubpension

# 2. Crear carpetas de vistas
mkdir -Force resources/views/layouts
mkdir -Force resources/views/auth
mkdir -Force resources/views/dashboard
mkdir -Force resources/views/clientes

# 3. Verificar que se crearon
Get-ChildItem resources/views/

================================================================

🗄️ PASO 6: CREAR CONTROLADORES

# 1. Controlador de autenticación
php artisan make:controller AuthController

# 2. Controlador del dashboard
php artisan make:controller DashboardController

# 3. Controlador de clientes (CRUD completo)
php artisan make:controller ClienteController --resource --model=Cliente

# 4. Componente layout
php artisan make:component Layout/AppLayout

================================================================

FASE 7: CREAR MODELOS ADICIONALES
powershell
# Modelos para catálogos
php artisan make:model CatalogoInstituto
php artisan make:model CatalogoRegimen
php artisan make:model CatalogoTramite
php artisan make:model CatalogoModalidad

# Modelos para cliente
php artisan make:model ClienteRfc
php artisan make:model ClienteNss
php artisan make:model ClienteCurp



================================================================
FASE 9: CREAR MIGRACIONES PARA SPATIE
powershell
# Ejecutar migraciones de Spatie
php artisan migrate

================================================================
FASE 10: CREAR SEEDERS

powershell
# 1. Seeder para roles y permisos
php artisan make:seeder RolePermissionSeeder

# 2. Seeder para admin
php artisan make:seeder AdminSeeder

# 3. Ejecutar seeders
php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=AdminSeeder
================================================================
FASE 11: CREAR VISTAS

C:\xampp\htdocs\clubpension\resources\views\layouts\app.blade.php - Layout Principal
C:\xampp\htdocs\clubpension\resources\views\auth\login.blade.php - Vista de login
C:\xampp\htdocs\clubpension\resources\views\dashboard\index.blade.php - vista del Dashboard

Crear vistas básicas del CRUD DE CLIENTES
1. ✅ C:\xampp\htdocs\clubpension\resources\views\clientes\index.blade.php - Listado de Clientes
2. ✅ C:\xampp\htdocs\clubpension\resources\views\clientes\_form.blade.php - Formulario de Cliente (compartido)
3. ✅ C:\xampp\htdocs\clubpension\resources\views\clientes\create.blade.php - Vista para Crear Cliente
4. ✅ C:\xampp\htdocs\clubpension\resources\views\clientes\edit.blade.php - Vista para Editar Cliente
5. ✅ C:\xampp\htdocs\clubpension\resources\views\clientes\show.blade.php - Vista de Detalle del Cliente

================================================================

✅ FASE 12: VERIFICACIÓN FINAL
powershell
# Limpiar caché
php artisan optimize:clear

# Verificar rutas
php artisan route:list

# Probar en tinker
php artisan tinker

php
// En tinker probar:
use App\Models\Usuario;

// 1. Verificar que el modelo existe
echo "Modelo: " . get_class(new Usuario) . "\n";

// 2. Contar usuarios
echo "Total usuarios: " . Usuario::count() . "\n";

// 3. Buscar admin
$admin = Usuario::where('email', 'dronces@hotmail.com')->first();
if ($admin) {
    echo "✅ Admin encontrado\n";
    echo "ID: " . $admin->id . "\n";
    echo "Email: " . $admin->email . "\n";
    echo "Tiene rol admin: " . ($admin->hasRole('admin') ? 'Sí' : 'No') . "\n";
    echo "Puede ver clientes: " . ($admin->can('ver clientes') ? 'Sí' : 'No') . "\n";
} else {
    echo "❌ Admin no encontrado\n";
}

================================================================

Flujo corregido:
text
Instalación → Paquetes → Configuración → Migraciones → Seeders → Controladores → Vistas

1. Instalar Laravel
   ↓
2. Crear BD en MySQL + Ejecutar schema.sql
   ↓
3. Configurar .env + key:generate
   ↓
4. Instalar paquetes (Spatie, UI, etc.)
   ↓
5. Crear usuario admin en MySQL (con hash)
   ↓
6. Configurar modelo Usuario personalizado
   ↓
7. Configurar Spatie (migraciones + config)
   ↓
8. Crear seeders de roles
   ↓
9. Crear controladores + modelos
   ↓
10. Crear vistas + rutas
   ↓
11. Verificar todo funciona

================================================================

CREAR RUTAS BÁSICAS
C:\xampp\htdocs\clubpension\routes\web.php

CREAR MODELOS PARA CATÁLOGOS
C:\xampp\htdocs\clubpension\app\Models\CatalogoInstituto.php
C:\xampp\htdocs\clubpension\app\Models\CatalogoRegimen.php
C:\xampp\htdocs\clubpension\app\Models\CatalogoTramite.php
C:\xampp\htdocs\clubpension\app\Models\CatalogoModalidad.php
C:\xampp\htdocs\clubpension\app\Models\ClienteRfc.php
C:\xampp\htdocs\clubpension\app\Models\ClienteNss.php
C:\xampp\htdocs\clubpension\app\Models\ClienteCurp.php

Crear AuthController
C:\xampp\htdocs\clubpension\app\Http\Controllers\AuthController.php
C:\xampp\htdocs\clubpension\app\Http\Controllers\DashboardController.php
C:\xampp\htdocs\clubpension\app\Http\Controllers\ClienteController.php

C:\xampp\htdocs\clubpension\app\Models\Cliente.php - modelo Cliente

C:\xampp\htdocs\clubpension\database\seeders\AdminSeeder.php

para asignar roles al usuario existente
C:\xampp\htdocs\clubpension\database\seeders\RolePermissionSeeder.php

=============================================================================
