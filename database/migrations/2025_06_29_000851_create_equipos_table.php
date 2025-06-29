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
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->string('modelo');
            $table->string('marca');
            $table->string('nombre');
            $table->foreignId('tipo_equipo_id')->constrained('tipo_equipos');
            $table->date('fecha_fabricacion');
            $table->decimal('potencia_kw', 8, 2);
            $table->integer('velocidad_rpm');
            $table->string('proveedor')->nullable();
            $table->text('informacion_adicional')->nullable();
            $table->integer('vigencia_anos')->default(10);
            $table->boolean('requiere_calibracion')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};