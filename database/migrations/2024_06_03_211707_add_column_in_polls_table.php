<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddColumnInPollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('polls', function (Blueprint $table) {
             if (Schema::hasColumn('polls', 'user_type_id')) {
                $table->dropColumn('user_type_id');
            }
            if (Schema::hasColumn('polls', 'is_draft')) {
                $table->dropColumn('is_draft');
            }
            if (Schema::hasColumn('polls', 'start_date')) {
                $table->dropColumn('start_date');
            }
            if (Schema::hasColumn('polls', 'end_date')) {
                $table->dropColumn('end_date');
            }
            if (Schema::hasColumn('polls', 'individual_type_id')) {
                $table->dropColumn('individual_type_id');
            }


            // Add the columns back in the correct order
            $table->unsignedBigInteger('user_type_id')->nullable()->after('area_id');
            $table->unsignedBigInteger('individual_type_id')->nullable()->after('user_type_id');
            $table->boolean('is_draft')->default(true)->after('individual_type_id');
            $table->timestamp('start_date')->nullable()->after('viewed');
            $table->timestamp('end_date')->nullable()->after('start_date');

            // Add foreign key constraints
            $table->foreign('user_type_id')->references('id')->on('lookups');
            $table->foreign('individual_type_id')->references('id')->on('lookups');
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
