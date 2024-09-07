<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->unsignedBigInteger('user_creator_id')->nullable();
            $table->string('message_body')->nullable();
            $table->unsignedBigInteger('parent_message_id')->nullable();
            $table->date('expiry_date');
            $table->boolean('is_reminder');
            
            $table->foreign('parent_message_id')->references('id')->on('messages');
            $table->foreign('user_creator_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
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
