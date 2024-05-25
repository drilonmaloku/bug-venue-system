<?php

namespace Database\Factories;

use App\Models\User;
use App\Modules\Logs\Models\Log;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class LogsFactory extends Factory
{

    protected $model = Log::class;

    public function definition(): array
    {

        $userIds = User::pluck('id');
        
        return [            
            "user_id" =>  $this->faker->randomElement($userIds),
            "updated_data" => $this->faker->sentence,
            "updated_at" => $this->faker->dateTimeThisMonth,
            "ttl" => $this->faker->numberBetween(0, 3),
            "previous_data" => $this->faker->sentence,
            "message" => $this->faker->sentence,
            "keep_alive" => $this->faker->boolean,
            "deletes_at" => $this->faker->dateTimeThisYear,
            "created_at" => $this->faker->dateTimeThisMonth,
            "context" => $this->faker->numberBetween(1, 4),
        ];
    }

}
