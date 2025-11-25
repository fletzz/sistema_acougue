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
        Schema::table('produtos', function (Blueprint $table) {
            $table->string('cfop', 4)->nullable()->after('cest');
            $table->string('csosn', 3)->nullable()->after('cfop'); // Para Simples Nacional
            $table->string('cst_icms', 3)->nullable()->after('csosn'); // Para outros regimes
            $table->decimal('aliquota_icms', 5, 2)->default(0)->after('cst_icms');
            $table->decimal('aliquota_pis', 5, 2)->default(0)->after('aliquota_icms');
            $table->decimal('aliquota_cofins', 5, 2)->default(0)->after('aliquota_pis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropColumn([
                'cfop',
                'csosn',
                'cst_icms',
                'aliquota_icms',
                'aliquota_pis',
                'aliquota_cofins'
            ]);
        });
    }
};
