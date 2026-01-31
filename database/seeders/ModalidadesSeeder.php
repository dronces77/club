<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModalidadesSeeder extends Seeder
{
    public function run()
    {
        // Verificar si ya existen las modalidades para evitar duplicados
        $modalidades = [
            ['codigo' => 'NA', 'nombre' => 'No Aplica', 'descripcion' => 'Modalidad no aplica', 'activo' => true],
            ['codigo' => 'CV', 'nombre' => 'Continuación Voluntaria', 'descripcion' => 'Continuación Voluntaria ISSSTE', 'activo' => true],
            // Agregar también las modalidades M10 y M40 si no existen
            ['codigo' => 'M10', 'nombre' => 'Modalidad 10', 'descripcion' => 'Modalidad 10 IMSS', 'activo' => true],
            ['codigo' => 'M40', 'nombre' => 'Modalidad 40', 'descripcion' => 'Modalidad 40 IMSS', 'activo' => true],
        ];
        
        foreach ($modalidades as $modalidad) {
            // Verificar si ya existe
            $existe = DB::table('catalogo_modalidades')
                ->where('codigo', $modalidad['codigo'])
                ->exists();
            
            if (!$existe) {
                DB::table('catalogo_modalidades')->insert([
                    'codigo' => $modalidad['codigo'],
                    'nombre' => $modalidad['nombre'],
                    'descripcion' => $modalidad['descripcion'],
                    'activo' => $modalidad['activo'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->command->info("Modalidad {$modalidad['codigo']} agregada.");
            } else {
                $this->command->info("Modalidad {$modalidad['codigo']} ya existe.");
            }
        }
        
        // También agregar trámites ISSSTE si no existen
        $tramitesIssste = [
            ['codigo' => 'POR', 'nombre' => 'Portabilidad IMSS', 'descripcion' => 'Portabilidad IMSS'],
            ['codigo' => 'INV', 'nombre' => 'Invalidez', 'descripcion' => 'Invalidez'],
            ['codigo' => 'RT', 'nombre' => 'Riesgo T', 'descripcion' => 'Riesgo de Trabajo'],
            ['codigo' => 'CEAV', 'nombre' => 'Cesantía', 'descripcion' => 'Cesantía'],
            ['codigo' => 'VIU', 'nombre' => 'Viudez', 'descripcion' => 'Viudez'],
            ['codigo' => 'ORF', 'nombre' => 'Orfandad', 'descripcion' => 'Orfandad'],
        ];
        
        foreach ($tramitesIssste as $tramite) {
            $existe = DB::table('catalogo_tramites')
                ->where('codigo', $tramite['codigo'])
                ->exists();
            
            if (!$existe) {
                DB::table('catalogo_tramites')->insert([
                    'codigo' => $tramite['codigo'],
                    'nombre' => $tramite['nombre'],
                    'descripcion' => $tramite['descripcion'],
                    'activo' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->command->info("Trámite {$tramite['codigo']} agregado.");
            }
        }
        
        // Agregar régimenes ISSSTE si no existen
        $regimenesIssste = [
            ['instituto_id' => 2, 'codigo' => '10MO', 'nombre' => 'Décimo'],
            ['instituto_id' => 2, 'codigo' => 'CI', 'nombre' => 'Cuentas Individuales'],
        ];
        
        foreach ($regimenesIssste as $regimen) {
            $existe = DB::table('catalogo_regimenes')
                ->where('instituto_id', $regimen['instituto_id'])
                ->where('codigo', $regimen['codigo'])
                ->exists();
            
            if (!$existe) {
                DB::table('catalogo_regimenes')->insert([
                    'instituto_id' => $regimen['instituto_id'],
                    'codigo' => $regimen['codigo'],
                    'nombre' => $regimen['nombre'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->command->info("Régimen {$regimen['codigo']} para ISSSTE agregado.");
            }
        }
    }
}