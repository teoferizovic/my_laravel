<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',256);
            $table->longText('body');
            $table->string('status',1)->default('N')->comment('N-new, R-read, D-deleted') ;            
            $table->unsignedInteger('reply_id')->nullable();
            $table->foreign('reply_id')->references('id')->on('messages');
            $table->unsignedInteger('sender_id');
            $table->foreign('sender_id')->references('id')->on('users');
            $table->unsignedInteger('reciver_id');
            $table->foreign('reciver_id')->references('id')->on('users');
            $table->softDeletes();
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
        Schema::dropIfExists('messages');
    }
}
