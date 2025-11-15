<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transformacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_origem_id')->constrained('produtos');
            $table->decimal('quantidade_origem', 10, 3);
            $table->foreignId('usuario_id')->constrained('users');
            $table->dateTime('data_transformacao');
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transformacoes');
    }
};

