<?php

namespace Database\Factories;

use App\Modules\Location\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class LocationFactory extends Factory
{

    protected $model = Location::class;

    public function definition(): array
    {
        return [
            'owner_id' => 1,
            'name' => fake()->text(50),
            'slug' => fake()->text(10),

            
        ];
    }

}
