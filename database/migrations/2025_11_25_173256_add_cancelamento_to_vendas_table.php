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
        Schema::table('vendas', function (Blueprint $table) {
            $table->string('motivo_cancelamento')->nullable()->after('status');
            $table->foreignId('usuario_cancelamento_id')->nullable()->constrained('users')->after('motivo_cancelamento');
            $table->dateTime('data_cancelamento')->nullable()->after('usuario_cancelamento_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendas', function (Blueprint $table) {
            $table->dropForeign(['usuario_cancelamento_id']);
            $table->dropColumn(['motivo_cancelamento', 'usuario_cancelamento_id', 'data_cancelamento']);
        });
    }
};
