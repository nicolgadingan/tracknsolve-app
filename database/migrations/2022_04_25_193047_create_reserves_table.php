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
        Schema::create('reserves', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status', 20);
            $table->string('category', 50);
            $table->string('key_id', 64);
            $table->integer('created_by');
            $table->timestamp('created_at');
        });

        DB::statement('alter table reserves auto_increment = 73800001');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reserves');
    }
};
