<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGuestNameToBookingsTable extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Add the guest_name column to store the guest's name
            $table->string('guest_name')->after('seat_id')->nullable();  // The guest name can be nullable
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Remove the guest_name column if rolling back
            $table->dropColumn('guest_name');
        });
    }
}
