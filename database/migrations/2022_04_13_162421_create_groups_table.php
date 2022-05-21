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
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('description', 255)->nullable();
            $table->string('status', 1);
            $table->integer('owner');
            $table->string('slug');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
        });

        DB::statement('alter table groups auto_increment = 400001');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
};
