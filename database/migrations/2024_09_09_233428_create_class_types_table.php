<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      // Adjusted `class_types` table
        Schema::create('class_types', function (Blueprint $table) {
            $table->id();
            $table->string('type_name');
            $table->string('booking_process');
            $table->timestamps();
        });

        // Add `class_type_id` to `classes` table
        Schema::table('classes', function (Blueprint $table) {
            $table->foreignId('class_type_id')->constrained('class_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_types');
    }
}
