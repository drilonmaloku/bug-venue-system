<?php

namespace Database\Factories;

use App\Modules\Clients\Models\Client;
use App\Modules\Reservations\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ReservationFactory extends Factory
{

    protected $model = Reservation::class;

    public function definition(): array
    {

        return [
            'venue_id' => fake()->randomNumber(1,5),
            'client_id' => fake()->randomNumber(1,50),
            'date' => fake()->date('d-m-Y'),
            'description' => fake()->text('50'),
            'full_day' => fake()->boolean(),
            'number_of_guests' => fake()->numberBetween(50,200),
            'current_payment' => fake()->numberBetween(50,200),
            'total_payment' => fake()->numberBetween(50,200),
        ];
    }

}
