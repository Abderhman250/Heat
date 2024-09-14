<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seat_points', function (Blueprint $table) {
            $table->id();
            $table->string('seat_number');
            $table->string('line');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('seat_points');
    }
}
