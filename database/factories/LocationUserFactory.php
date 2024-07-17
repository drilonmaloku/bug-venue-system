<?php

namespace Database\Factories;

use App\Models\User;
use App\Modules\Location\Models\Location;
use App\Modules\Users\Models\LocationUser;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class LocationUserFactory extends Factory
{

    protected $model = LocationUser::class;

    public function definition(): array
    {
        $userIds = User::pluck('id')->toArray();
        $locationIds = Location::pluck('id')->toArray();
        return [
            'user_id' => $this->faker->randomElement($userIds),
            'location_id' => $this->faker->randomElement($locationIds), 
        ];
    }

}
