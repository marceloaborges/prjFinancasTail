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
        Schema::create('plano_contas', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('plano_conta_tipo_id')->unsigned();
            $table->foreign('plano_conta_tipo_id')
                    ->references('id')
                    ->on('plano_conta_tipos');
            $table->integer('plano_conta_classificacao_id')->unsigned();
            $table->foreign('plano_conta_classificacao_id')
                    ->references('id')
                    ->on('plano_conta_classificacoes');
            $table->integer('operacao_id')->unsigned();
            $table->foreign('operacao_id')
                    ->references('id')
                    ->on('operacoes');
            $table->string('descricao',100)->unique();
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
        Schema::dropIfExists('plano_contas');
    }
};
