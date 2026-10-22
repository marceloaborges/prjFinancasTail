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
        Schema::create('logs', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users');
            $table->integer('log_tipo_id')->unsigned();
            $table->foreign('log_tipo_id')
                    ->references('id')
                    ->on('log_tipos');
            $table->string('acao',100);
            $table->string('metodo', 100)->nullable();
            $table->text('descricao')->nullable();
            $table->string('tabela',100)->nullable();
            $table->integer('registro_id')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('log_tipo_id');
            $table->index(['log_tipo_id','created_at']);
            $table->index(['user_id','created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
