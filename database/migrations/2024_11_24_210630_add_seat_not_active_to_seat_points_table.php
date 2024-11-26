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
        Schema::table('seat_points', function (Blueprint $table) {
            $table->boolean('seat_not_active')->default(false)->after('class_id'); // Default to false, meaning the seat is active by default

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seat_points', function (Blueprint $table) {
            $table->dropColumn('seat_not_active');
        });
    }
};
