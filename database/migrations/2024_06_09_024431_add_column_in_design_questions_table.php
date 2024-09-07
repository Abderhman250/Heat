<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInDesignQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('design_questions', function (Blueprint $table) {

            $table->unsignedBigInteger('design_id')->nullable()->after('id');
            $table->foreign('design_id')->references('id')->on('lookups');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('design_questions', function (Blueprint $table) {
            //
        });
    }
}
