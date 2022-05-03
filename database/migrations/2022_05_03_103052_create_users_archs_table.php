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
        Schema::create('users_archs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('role');
            $table->string('first_name', 50);
            $table->string('middle_name')->nullable();
            $table->string('last_name', 50);
            $table->string('username', 20)->unique();
            $table->integer('group_id')->nullable();
            $table->string('email', 50)->unique();
            $table->string('contact_no')->nullable();
            $table->integer('deleted_by');
            $table->date('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_archs');
    }
};
