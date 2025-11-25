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
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('inscricao_estadual', 14)->nullable()->after('cpf_cnpj');
            $table->string('inscricao_municipal', 15)->nullable()->after('inscricao_estadual');
            $table->string('indicador_ie', 1)->default('9')->after('inscricao_municipal'); // 1=Contribuinte, 2=Isento, 9=Não contribuinte
            $table->string('logradouro', 100)->nullable()->after('endereco');
            $table->string('numero', 10)->nullable()->after('logradouro');
            $table->string('complemento', 60)->nullable()->after('numero');
            $table->string('bairro', 60)->nullable()->after('complemento');
            $table->string('cep', 8)->nullable()->after('bairro');
            $table->integer('codigo_municipio_ibge')->nullable()->after('cep');
            $table->string('municipio', 60)->nullable()->after('codigo_municipio_ibge');
            $table->char('uf', 2)->nullable()->after('municipio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn([
                'inscricao_estadual',
                'inscricao_municipal',
                'indicador_ie',
                'logradouro',
                'numero',
                'complemento',
                'bairro',
                'cep',
                'codigo_municipio_ibge',
                'municipio',
                'uf'
            ]);
        });
    }
};
