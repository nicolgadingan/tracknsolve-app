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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('category', 20);
            $table->string('action', 20);
            $table->string('key_id1', 30);
            $table->string('key_id2', 30)->nullable();
            $table->string('key_id3', 30)->nullable();
            $table->text('description')->nullable();
            $table->integer('event_by');
            $table->timestamp('event_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
};
