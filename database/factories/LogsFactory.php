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
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = Log::class;
    public function definition(): array
    {
        $user = (new UserFactory)->create();
        $user = User::find($user->id);
        
        return [            
            "user_id" =>  $user,
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

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
