<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('entrada_mercadoria_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrada_mercadoria_id')->constrained('entradas_mercadoria')->onDelete('cascade');
            $table->foreignId('produto_id')->constrained('produtos');
            $table->decimal('quantidade', 10, 3);
            $table->decimal('preco_custo', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('entrada_mercadoria_itens');
    }
};

