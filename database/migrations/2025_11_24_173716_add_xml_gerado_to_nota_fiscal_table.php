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
        Schema::table('nota_fiscal', function (Blueprint $table) {
            $table->longText('xml_gerado')->nullable()->after('versao_leiaute');
            $table->longText('xml_assinado')->nullable()->after('xml_gerado');
            $table->longText('xml_autorizado')->nullable()->after('xml_assinado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nota_fiscal', function (Blueprint $table) {
            $table->dropColumn(['xml_gerado', 'xml_assinado', 'xml_autorizado']);
        });
    }
};
