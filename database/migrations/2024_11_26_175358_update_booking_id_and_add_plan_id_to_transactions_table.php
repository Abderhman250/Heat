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
        Schema::table('transactions', function (Blueprint $table) {
            // Drop the foreign key and column for booking_id if no longer needed
            $table->dropForeign(['booking_id']);  // Remove booking_id foreign key constraint
            $table->dropColumn('booking_id');     // Remove the booking_id column

            // Add plan_id for the relation with plans
            $table->unsignedBigInteger('plan_id')->nullable(); // Add the plan_id column
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade'); // Foreign key for plans
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Rollback changes if necessary
            $table->dropForeign(['plan_id']); // Drop foreign key for plan_id
            $table->dropColumn('plan_id');    // Drop the plan_id column

            // Re-add the booking_id foreign key and column
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });
    }
};
