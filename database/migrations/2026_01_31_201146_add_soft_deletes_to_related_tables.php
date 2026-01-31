<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Agregar deleted_at a tablas relacionadas si no existen
        $tables = [
            'clientes_curp',
            'clientes_nss',
            'clientes_rfc',
            'clientes_contacto',
        ];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->softDeletes();
                });
            }
        }
    }

    public function down()
    {
        $tables = [
            'clientes_curp',
            'clientes_nss',
            'clientes_rfc',
            'clientes_contacto',
        ];
        
        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropSoftDeletes();
                });
            }
        }
    }
};