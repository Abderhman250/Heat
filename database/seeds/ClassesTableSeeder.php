<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use Illuminate\Database\Seeder;

class ClassesTableSeeder extends Seeder
{
    public function run()
    {
        // Create 10 classes
        ClassModel::factory()->count(3)->create();
    }
}
