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
        Schema::create('evento_nfe', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nota_fiscal_id')->constrained('nota_fiscal')->onDelete('cascade');
            $table->string('tipo_evento', 30);
            $table->text('descricao');
            $table->string('protocolo_evento', 20)->nullable();
            $table->dateTime('data_evento');
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
        Schema::dropIfExists('evento_nfe');
    }
};
