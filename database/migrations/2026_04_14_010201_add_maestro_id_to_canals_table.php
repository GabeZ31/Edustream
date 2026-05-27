<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('canals', function (Blueprint $table) {
            $table->foreignId('maestro_id')->nullable()->constrained('users')->after('descripcion');
        });
    }

    public function down()
    {
        Schema::table('canals', function (Blueprint $table) {
            $table->dropColumn('maestro_id');
        });
    }
};
