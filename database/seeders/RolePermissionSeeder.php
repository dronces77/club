<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar cache de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos del CRM
        $permisos = [
            // Dashboard
            'ver dashboard',
            
            // Clientes
            'ver clientes', 'crear clientes', 'editar clientes', 'eliminar clientes',
            'exportar clientes', 'importar clientes',
            
            // Documentos
            'ver documentos', 'subir documentos', 'descargar documentos', 'eliminar documentos',
            
            // Reportes
            'ver reportes', 'generar reportes',
            
            // Usuarios
            'ver usuarios', 'crear usuarios', 'editar usuarios', 'eliminar usuarios',
            'asignar roles',
            
            // Catálogos
            'ver catalogos', 'editar catalogos',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate([
                'name' => $permiso,
                'guard_name' => 'web'
            ]);
        }

        // Crear roles
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $supervisor = Role::firstOrCreate(['name' => 'supervisor', 'guard_name' => 'web']);
        $operador = Role::firstOrCreate(['name' => 'operador', 'guard_name' => 'web']);

        // Asignar todos los permisos al admin
        $admin->givePermissionTo(Permission::all());

        // Asignar permisos a supervisor
        $supervisor->givePermissionTo([
            'ver dashboard',
            'ver clientes', 'crear clientes', 'editar clientes',
            'ver documentos', 'subir documentos',
            'ver reportes',
            'ver usuarios',
        ]);

        // Asignar permisos a operador
        $operador->givePermissionTo([
            'ver dashboard',
            'ver clientes', 'crear clientes', 'editar clientes',
            'ver documentos', 'subir documentos',
        ]);

        // Asignar rol admin al usuario existente
        $usuarioAdmin = Usuario::where('email', 'dronces@hotmail.com')->first();
        if ($usuarioAdmin) {
            $usuarioAdmin->assignRole('admin');
            echo "✅ Rol 'admin' asignado al usuario: " . $usuarioAdmin->email . "\n";
        } else {
            echo "⚠️ Usuario admin no encontrado. Creando...\n";
            
            $usuarioAdmin = Usuario::create([
                'username' => 'admin',
                'email' => 'dronces@hotmail.com',
                'password_hash' => bcrypt('admin123'),
                'nombre' => 'Administrador',
                'apellidos' => 'Sistema',
                'rol' => 'admin',
                'activo' => true,
            ]);
            
            $usuarioAdmin->assignRole('admin');
            echo "✅ Usuario admin creado y rol asignado\n";
        }
    }
}
