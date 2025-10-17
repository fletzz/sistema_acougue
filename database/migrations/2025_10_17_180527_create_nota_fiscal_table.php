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
        Schema::create('nota_fiscal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venda_id')->nullable()->constrained('vendas');
            $table->foreignId('emitente_id')->constrained('emitente');
            $table->foreignId('destinatario_id')->nullable()->constrained('clientes');
            $table->integer('numero');
            $table->integer('serie');
            $table->char('modelo', 2);
            $table->string('tipo_operacao', 1); 
            $table->string('finalidade', 1);
            $table->string('natureza_operacao', 60);
            $table->string('ambiente', 1); 
            $table->string('tipo_emissao', 1)->default('1');
            $table->dateTime('data_emissao');
            $table->dateTime('data_saida_entrada')->nullable();
            $table->string('chave_acesso', 44)->unique()->nullable();
            $table->string('protocolo_autorizacao', 20)->nullable();
            $table->string('status', 20)->default('digitacao');
            $table->decimal('valor_total_produtos', 15, 2)->default(0);
            $table->decimal('valor_frete', 15, 2)->default(0);
            $table->decimal('valor_seguro', 15, 2)->default(0);
            $table->decimal('valor_desconto', 15, 2)->default(0);
            $table->decimal('valor_impostos', 15, 2)->default(0);
            $table->decimal('valor_total_nfe', 15, 2)->default(0);
            $table->text('informacoes_adicionais')->nullable();
            $table->string('versao_leiaute', 10)->nullable();
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
        Schema::dropIfExists('nota_fiscal');
    }
};
