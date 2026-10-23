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
        Schema::create('cartoes', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('descricao',50)->unique();
            $table->integer('dia_fechamento');
            $table->integer('dia_vencimento');
            $table->integer('bandeira_id')->unsigned();
            $table->foreign('bandeira_id')
                    ->references('id')
                    ->on('bandeiras');
            $table->integer('conta_id')->unsigned()->nullable();
            $table->foreign('conta_id')
                    ->references('id')
                    ->on('contas');
            $table->string('numero_cartao',50)->nullable();
            $table->string('nome',100)->nullable();
            $table->date('validade')->nullable();
            $table->double('limite',10,2);
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
        Schema::dropIfExists('cartoes');
    }
};
