<?php

namespace Database\Factories;

use App\Modules\Venues\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class VenueFactory extends Factory
{

    protected $model = Venue::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->text(50),
            'capacity' => fake()->numberBetween(50,200),
        ];
    }

}
