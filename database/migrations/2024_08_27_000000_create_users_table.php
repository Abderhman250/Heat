<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->nullable();
            $table->string('country_code')->nullable();
  
            $table->boolean('gender')->nullable();
            $table->string('email')->nullable()->unique();
   

            $table->date('dob')->nullable();
            $table->text('photo')->nullable();

            $table->string('password')->nullable();
            $table->string('facebook_id')->nullable();
            $table->string('google_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();

            $table->boolean('is_coache')->default(false);
            $table->boolean('is_active')->default(true);

            $table->string('otp', 255)->nullable();

            $table->unsignedBigInteger('number_class')->default(0);
            
            $table->foreignId('level_id')->nullable()->constrained('levels')->onDelete('cascade');


            $table->rememberToken();
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
}
