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
        Schema::create('plano_conta_tipos', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('descricao',20)->unique();
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
        Schema::dropIfExists('plano_conta_tipos');
    }
};
