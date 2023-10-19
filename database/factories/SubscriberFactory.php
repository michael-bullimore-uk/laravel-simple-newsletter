<?php

namespace MIBU\Newsletter\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use MIBU\Newsletter\Models\Subscriber;

class SubscriberFactory extends Factory
{
    protected $model = Subscriber::class;

    public function definition(): array
    {
        return [
            'email' => $this->faker->email(),
        ];
    }
}
