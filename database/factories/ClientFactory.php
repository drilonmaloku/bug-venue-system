<?php

namespace Database\Factories;

use App\Modules\Clients\Models\Client;
use App\Modules\Venues\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ClientFactory extends Factory
{

    protected $model = Client::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'phone_number' => fake()->phoneNumber(),
            'additional_phone_number' => fake()->phoneNumber(),
            'location_id' => 1,

        ];
    }
}
