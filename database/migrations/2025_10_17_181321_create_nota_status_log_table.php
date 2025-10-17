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
        Schema::create('nota_status_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nota_fiscal_id')->constrained('nota_fiscal')->onDelete('cascade');
            $table->string('status_anterior', 20);
            $table->string('status_novo', 20);
            $table->dateTime('data_alteracao');
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios');
            $table->text('observacao')->nullable();
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
        Schema::dropIfExists('nota_status_log');
    }
};
