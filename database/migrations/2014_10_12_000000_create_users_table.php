<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('role');
            $table->string('status', 1);
            $table->string('first_name', 50);
            $table->string('middle_name')->nullable();
            $table->string('last_name', 50);
            $table->string('username', 20)->unique();
            $table->integer('group_id')->nullable();
            $table->string('slug')->unique();
            $table->string('email', 50)->unique();
            $table->string('contact_no')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
        });

        DB::statement('alter table users auto_increment = 700001');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
