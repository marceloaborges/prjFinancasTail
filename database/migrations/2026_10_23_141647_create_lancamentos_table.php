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
        Schema::create('lancamentos', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('forma_pagto_id')->unsigned();
            $table->foreign('forma_pagto_id')
                    ->references('id')
                    ->on('forma_pagtos');
            $table->integer('cartao_id')->unsigned()->nullable();
            $table->foreign('cartao_id')
                    ->references('id')
                    ->on('cartoes');
            $table->integer('lancamento_tipo_id')->unsigned();
            $table->foreign('lancamento_tipo_id')
                    ->references('id')
                    ->on('lancamento_tipos');
            $table->integer('plano_conta_id')->unsigned();
            $table->foreign('plano_conta_id')
                    ->references('id')
                    ->on('plano_contas');
            $table->integer('conta_id')->unsigned();
            $table->foreign('conta_id')
                    ->references('id')
                    ->on('contas');
            $table->date('data_operacao');
            $table->date('data_vencimento');
            $table->date('data_pagamento')->nullable();           
            $table->string('descricao',255);
            $table->double('valor',10,2);
            $table->double('desconto',10,2)->nullable();
            $table->double('acrescimo',10,2)->nullable();
            $table->double('valor_realizado',10,2);
            $table->text('observacao')->nullable();            
            $table->boolean('consolidada')->default(0);
            $table->integer('numero_parcela');
            $table->integer('total_parcelas');
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
        Schema::dropIfExists('lancamentos');
    }
};
