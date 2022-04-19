<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status');
            $table->string('title', 100);
            $table->text('description', 4000);
            $table->integer('group_id');
            $table->integer('assignee')->nullable();
            $table->integer('reporter');
            $table->timestamps();
        });

        DB::statement('alter table tickets auto_increment = 84253800001');
    }
    
     /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
