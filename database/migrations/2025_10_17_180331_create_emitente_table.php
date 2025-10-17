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
        Schema::create('emitente', function (Blueprint $table) {
            $table->id();
            $table->string('cnpj', 14)->unique();
            $table->string('razao_social', 120);
            $table->string('nome_fantasia', 60);
            $table->string('inscricao_estadual', 14)->nullable();
            $table->string('inscricao_municipal', 15)->nullable();
            $table->string('crt', 1);

            $table->string('logradouro', 100);
            $table->string('numero', 10);
            $table->string('complemento', 60)->nullable();
            $table->string('bairro', 60);
            $table->integer('codigo_municipio');
            $table->string('municipio', 60);
            $table->char('uf', 2);
            $table->string('cep', 8);
            $table->string('telefone', 15)->nullable();
            $table->string('email', 120)->nullable();
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
        Schema::dropIfExists('emitente');
    }
};
