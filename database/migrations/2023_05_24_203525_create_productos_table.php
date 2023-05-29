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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string("nombre");
            $table->text("descripcion");
            $table->string("categoria");
            $table->string("etiqueta");
            $table->integer("cantidad");
            $table->float("precio");
            $table->string("info_adicional");
            $table->float("valoracion");
            $table->string("sku")->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
