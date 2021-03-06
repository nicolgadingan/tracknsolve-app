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
            $table->string('id', 20)->unique();
            $table->string('status', 20);
            $table->string('priority', 20);
            $table->string('title', 100);
            $table->text('description', 4000);
            $table->integer('group_id');
            $table->integer('assignee')->nullable()->default(null);
            $table->integer('reporter');
            $table->timestamps();
        });
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
