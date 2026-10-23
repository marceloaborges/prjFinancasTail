<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contas', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('conta_tipo_id')->unsigned();
            $table->foreign('conta_tipo_id')
                    ->references('id')
                    ->on('conta_tipos');
            $table->integer('banco_id')->unsigned();
            $table->foreign('banco_id')
                    ->references('id')
                    ->on('bancos');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users');
            $table->string('agencia',20)->nullable();
            $table->string('conta',20)->nullable();
            $table->string('descricao',50);
            $table->double('saldo_inicial',10,2);
            $table->boolean('active')->default(1)->nullable();
            $table->integer('user_add')->nullable();
            $table->integer('user_upd')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contas');
    }
};
