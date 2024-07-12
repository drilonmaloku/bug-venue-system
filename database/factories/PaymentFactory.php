<?php

namespace Database\Factories;


use App\Modules\Payments\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;


class PaymentFactory extends Factory
{

    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'reservation_id' => fake()->numberBetween(1,50),
            'client_id' => fake()->numberBetween(1,50),
            'value' => fake()->numberBetween(50,200),
            'notes' => fake()->text(40),
            'date' => fake()->date(),
            'location_id' => 1,


        ];
    }
}
