<?php

namespace Database\Seeders;

use App\Models\BookingClassUser;
use Illuminate\Database\Seeder;

class BookingClassUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BookingClassUser::factory()->count(10)->create();

    }
}
