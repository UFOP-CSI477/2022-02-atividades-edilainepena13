<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pessoas', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 256);
            $table->string('rua', 128);
            $table->string('numero', 10);
            $table->string('complemento', 128);
            $table->string('documento', 30)->unique();
            $table->foreignId('cidade_id')
                ->constrained('cidades')
                ->onDelete('cascade');
            $table->foreignId('tipo_id')
                ->constrained('tipos_sanguineos')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pessoas');
    }
};
