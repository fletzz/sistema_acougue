<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transformacao_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transformacao_id')->constrained('transformacoes')->onDelete('cascade');
            $table->foreignId('produto_destino_id')->constrained('produtos');
            $table->decimal('quantidade', 10, 3);
            $table->string('tipo')->default('corte');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transformacao_itens');
    }
};

