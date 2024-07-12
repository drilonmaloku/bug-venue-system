<?php

namespace Database\Factories;

use App\Modules\Menus\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class MenuFactory extends Factory
{

    protected $model = Menu::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->text(50),
            'price' => fake()->numberBetween(15,35),
            'location_id' => 1,

        ];
    }

}
