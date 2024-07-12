<?php

namespace Database\Factories;


use App\Modules\Expenses\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;


class ExpenseFactory extends Factory
{

    protected $model = Expense::class;

    public function definition(): array
    {
        return [
            'user_id' => 1,
            'date' => fake()->date(),
            'description' => fake()->text(40),
            'amount' => fake()->numberBetween(30,500),
            'location_id' => 1,


        ];
    }
}
