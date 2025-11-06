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
        Schema::create('caixa_movimentacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caixa_id')->constrained('caixas');
            $table->foreignId('usuario_id')->constrained('users');
            $table->foreignId('forma_pagamento_id')->nullable()->constrained('formas_pagamento');
            $table->enum('tipo_movimentacao', ['entrada', 'saida', 'venda', 'suprimento', 'sangria']);
            $table->decimal('valor', 10, 2);
            $table->dateTime('data_movimentacao');
            $table->text('observacao')->nullable();
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
        Schema::dropIfExists('caixa_movimentacoes');
    }
};
