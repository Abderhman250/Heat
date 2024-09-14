<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

abstract class BaseFactory extends Factory
{
    protected $faker;

    public function __construct()
    {
        parent::__construct();
        $this->faker = FakerFactory::create('en_US');
    }
}
