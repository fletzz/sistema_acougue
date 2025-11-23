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
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('codigo_barras')->nullable(); //permite q o campo fique vazio
            $table->string('unidade_medida');
            $table->decimal('preco_venda', 10, 2);
            $table->decimal('estoque_atual', 10, 3)->default(0); // 3 casas decimais para peso
            $table->decimal('estoque_minimo', 10, 3)->default(0);
            $table->boolean('ativo')->default(true); //já nasce ativo
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->string('ncm_codigo')->nullable();
            $table->integer('origem_mercadoria')->nullable();
            $table->string('cest')->nullable();
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
        Schema::dropIfExists('produtos');
    }
};
