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
        Schema::create('vendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes');
            $table->foreignId('usuario_id')->constrained('users');
            $table->dateTime('data_venda');
            $table->decimal('valor_total_produtos', 10, 2);
            $table->decimal('valor_desconto', 10, 2)->default(0);
            $table->decimal('valor_total_final', 10, 2);
            $table->string('status')->default('finalizada');
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
        Schema::dropIfExists('vendas');
    }
};
