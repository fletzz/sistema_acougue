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
        Schema::create('transporte', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nota_fiscal_id')->constrained('nota_fiscal')->onDelete('cascade');
            $table->string('modalidade_frete', 1);
            $table->string('cnpj_transportador', 14)->nullable();
            $table->string('nome_transportador', 120)->nullable();
            $table->string('ie_transportador', 14)->nullable();
            $table->string('endereco_transportador', 120)->nullable();
            $table->string('municipio', 60)->nullable();
            $table->char('uf', 2)->nullable();
            $table->string('placa_veiculo', 8)->nullable();
            $table->char('uf_veiculo', 2)->nullable();
            $table->string('especie', 60)->nullable();
            $table->string('numeracao', 60)->nullable();
            $table->integer('quantidade_volumes')->nullable();
            $table->decimal('peso_bruto', 15, 3)->nullable();
            $table->decimal('peso_liquido', 15, 3)->nullable();
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
        Schema::dropIfExists('transporte');
    }
};
