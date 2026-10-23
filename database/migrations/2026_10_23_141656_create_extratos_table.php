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
        Schema::create('extratos', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('lancamento_id')->unsigned();
            $table->foreign('lancamento_id')
                    ->references('id')
                    ->on('lancamentos');
            $table->integer('conta_id')->unsigned();
            $table->foreign('conta_id')
                    ->references('id')
                    ->on('contas');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users');
            $table->date('data');
            $table->double('valor',10,2);
            $table->double('saldo',10,2);
            $table->text('observacao')->nullable();
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
        Schema::dropIfExists('extratos');
    }
};
