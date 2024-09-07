<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveTypeAndAreaFromPollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('polls', function (Blueprint $table) {
            //
   
            $table->dropColumn(['type', 'area']);
            $table->unsignedBigInteger('type_id')->nullable()->after('photo');
            $table->unsignedBigInteger('area_id')->nullable()->after('type_id');

            $table->foreign('type_id')->references('id')->on('lookups');
            $table->foreign('area_id')->references('id')->on('lookups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('polls', function (Blueprint $table) {
            //
        });
    }
}
