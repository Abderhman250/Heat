<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLookupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lookups', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('key');
            $table->string('value');
            
            $table->string('column_id')->nullable();
            $table->text('description')->nullable();
            $table->unique(['type', 'key']);
            $table->string('referans_key')->nullable();
            $table->timestamps();
        });

        Schema::table('lookups', function (Blueprint $table) {
            $table->index(['type', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lookups');
    }
}
