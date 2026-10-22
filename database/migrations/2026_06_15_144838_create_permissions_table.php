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
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('permission_group_id')->unsigned();
            $table->foreign('permission_group_id')
                    ->references('id')
                    ->on('permission_groups');
            $table->string('name',60);
            $table->string('description',255);
            $table->timestamps();
        });

        Schema::create('permission_role', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('permission_id')->unsigned();
            $table->foreign('permission_id')
                    ->references('id')
                    ->on('permissions');
            $table->integer('role_id')->unsigned();
            $table->foreign('role_id')
                    ->references('id')
                    ->on('roles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
    }
};
