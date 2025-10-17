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
        Schema::create('item_nfe', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nota_fiscal_id')->constrained('nota_fiscal')->onDelete('cascade');
            $table->foreignId('produto_id')->constrained('produtos');
            $table->integer('ordem');
            $table->string('codigo_item', 20);
            $table->string('descricao', 120);
            $table->string('ncm', 8);
            $table->string('cest', 7)->nullable();
            $table->string('cfop', 4);
            $table->string('unidade', 6);
            $table->decimal('quantidade', 15, 4);
            $table->decimal('valor_unitario', 15, 4);
            $table->decimal('valor_total', 15, 2);
            $table->decimal('desconto_item', 15, 2)->default(0);
            $table->string('origem', 1);
            $table->string('cst_icms', 3)->nullable();
            $table->decimal('aliquota_icms', 5, 2)->default(0);
            $table->decimal('valor_icms', 15, 2)->default(0);
            $table->string('cst_pis', 2)->nullable();
            $table->decimal('aliquota_pis', 5, 2)->default(0);
            $table->decimal('valor_pis', 15, 2)->default(0);
            $table->string('cst_cofins', 2)->nullable();
            $table->decimal('aliquota_cofins', 5, 2)->default(0);
            $table->decimal('valor_cofins', 15, 2)->default(0);
            $table->decimal('valor_ipi', 15, 2)->default(0);
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
        Schema::dropIfExists('item_nfe');
    }
};
