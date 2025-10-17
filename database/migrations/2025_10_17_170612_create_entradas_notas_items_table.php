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
        Schema::create('entradas_notas_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrada_nota_id')->constrained('entradas_notas')->onDelete('cascade');
            $table->foreignId('produto_id')->constrained('produtos');
            $table->decimal('quantidade', 10, 3);
            $table->decimal('preco_custo_unitario', 10, 2);
            $table->string('cfop')->nullable();
            $table->string('cst_icms')->nullable();
            $table->decimal('base_calculo_icms', 10, 2)->default(0);
            $table->decimal('aliquota_icms', 5, 2)->default(0);
            $table->decimal('valor_icms', 10, 2)->default(0);
            $table->string('cst_pis')->nullable();
            $table->decimal('base_calculo_pis', 10, 2)->default(0);
            $table->decimal('aliquota_pis', 5, 2)->default(0);
            $table->decimal('valor_pis', 10, 2)->default(0);
            $table->string('cst_cofins')->nullable();
            $table->decimal('base_calculo_cofins', 10, 2)->default(0);
            $table->decimal('aliquota_cofins', 5, 2)->default(0);
            $table->decimal('valor_cofins', 10, 2)->default(0);
            $table->decimal('valor_ipi', 10, 2)->default(0);
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
        Schema::dropIfExists('entradas_notas_items');
    }
};
