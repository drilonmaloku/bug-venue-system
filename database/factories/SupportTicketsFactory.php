<?php

namespace Database\Factories;

use App\Modules\SupportTickets\Models\SupportTicket;

use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class SupportTicketsFactory extends Factory
{

    protected $model = SupportTicket::class;

    public function definition(): array
    {
        return [
            'user_id' => 1,
            'resolver_id' => 1,
            'title' => fake()->text('10'),
            'description' => fake()->text('50'),
            'attachment' => fake()->text('25'),
            'attachment' => fake()->numberBetween(0,1),
        ];
    }
}
