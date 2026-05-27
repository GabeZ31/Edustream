<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('canals', function (Blueprint $table) {
            $table->string('codigo_acceso', 10)->nullable()->unique();
        });

        // Generar códigos únicos para los canales existentes
        $canales = DB::table('canals')->get();
        foreach ($canales as $canal) {
            $codigo = strtoupper(Str::random(6));
            // Asegurar unicidad
            while (DB::table('canals')->where('codigo_acceso', $codigo)->exists()) {
                $codigo = strtoupper(Str::random(6));
            }
            DB::table('canals')->where('id', $canal->id)->update(['codigo_acceso' => $codigo]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('canals', function (Blueprint $table) {
            $table->dropColumn('codigo_acceso');
        });
    }
};
