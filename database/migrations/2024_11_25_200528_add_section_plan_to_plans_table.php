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
        Schema::table('plans', function (Blueprint $table) {
         
            $table->foreignId('section_plan_id')->after('class_id')->constrained()->onDelete('cascade');  // Foreign key referencing section_plans table
        });
    }
    
    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropForeign(['section_plan_id']);
            $table->dropColumn('section_plan_id');
        });
    }
};
