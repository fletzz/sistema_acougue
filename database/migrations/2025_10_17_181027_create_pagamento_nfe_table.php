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
        Schema::create('pagamento_nfe', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nota_fiscal_id')->constrained('nota_fiscal')->onDelete('cascade');
            $table->foreignId('forma_pagamento_id')->constrained('formas_pagamento');
            $table->decimal('valor_pagamento', 15, 2);
            $table->decimal('troco', 15, 2)->default(0);
            $table->string('tipo_integracao', 1)->nullable();
            $table->string('cnpj_credenciadora', 14)->nullable();
            $table->string('bandeira', 10)->nullable();
            $table->string('numero_autorizacao', 20)->nullable();
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
        Schema::dropIfExists('pagamento_nfe');
    }
};
