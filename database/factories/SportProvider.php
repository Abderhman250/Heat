<?php

namespace Database\Factories;

use Faker\Provider\Base;

class SportProvider extends Base
{
    protected static $sportClasses = [
        'Yoga Basics',
        'Advanced Pilates',
        'Beginner Spin Class',
        'High-Intensity Interval Training',
        'Cardio Kickboxing',
        'Zumba Dance Fitness',
        'Strength and Conditioning',
        'Bootcamp Challenge',
        'Aqua Aerobics',
        'CrossFit Fundamentals',
    ];

    public function sportClassName()
    {
        return static::randomElement(static::$sportClasses);
    }

    public function sportClassDescription()
    {
        return static::randomElement([
            'An energetic class focusing on cardiovascular fitness.',
            'A relaxing yoga session to improve flexibility and mindfulness.',
            'High-intensity interval training to boost your metabolism.',
            'Learn the fundamentals of CrossFit in this beginner-friendly class.',
            'Dance your way to fitness with our fun Zumba sessions.',
            'Build strength and endurance in this challenging bootcamp.',
            'Join our spin class for a high-energy cycling workout.',
            'Improve your core strength with our Pilates class.',
            'Experience the benefits of water resistance in aqua aerobics.',
            'Kick and punch your way to fitness in cardio kickboxing.',
        ]);
    }
}
