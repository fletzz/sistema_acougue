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
        Schema::create('entradas_notas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fornecedor_id')->constrained('fornecedores');
            $table->string('numero_nota');
            $table->string('serie');
            $table->string('chave_acesso', 44)->unique();
            $table->dateTime('data_emissao');
            $table->dateTime('data_entrada');
            $table->decimal('valor_total_nota', 10 , 2);
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
        Schema::dropIfExists('entradas_notas');
    }
};
