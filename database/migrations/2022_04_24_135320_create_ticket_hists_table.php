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
        Schema::create('ticket_hists', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id', 20);
            $table->string('status', 20);
            $table->string('priority', 20);
            $table->string('title', 100);
            $table->text('description', 4000);
            $table->integer('group_id');
            $table->integer('assignee')->nullable();
            $table->integer('reporter');
            $table->integer('created_by');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_hists');
    }
};
