<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recursos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 150);
            $table->foreignId('canal_id')->constrained('canals');
            $table->string('descripcion', 300)->nullable();
            $table->string('tipo');        // video, pdf, documento, otro
            $table->string('archivo');     // ruta del archivo en storage
            $table->string('torrent')->nullable(); // solo para videos P2P
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recursos');
    }
};
