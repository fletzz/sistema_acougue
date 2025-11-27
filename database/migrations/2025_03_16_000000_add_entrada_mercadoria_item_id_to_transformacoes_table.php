<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transformacoes', function (Blueprint $table) {
            $table->foreignId('entrada_mercadoria_item_id')
                ->nullable()
                ->after('id')
                ->constrained('entrada_mercadoria_itens')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('transformacoes', function (Blueprint $table) {
            $table->dropForeign(['entrada_mercadoria_item_id']);
            $table->dropColumn('entrada_mercadoria_item_id');
        });
    }
};
