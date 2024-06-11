<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\ClientFactory;
use Database\Factories\LogsFactory;
use Database\Factories\MenuFactory;
use Database\Factories\PaymentFactory;
use Database\Factories\ReservationFactory;
use Database\Factories\UserFactory;
use Database\Factories\VenueFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;


class ProductionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->seedRoles();
        $this->seedMainUser();
    }


    public function seedMainUser() {
        $user = User::create([
            'username' => 'admin',
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'drilon.maloku13@gmail.com',
            'password' => bcrypt('loniloni13')
        ]);
        $user->assignRole(User::ROLE_SUPERAMDIN);
    }

    public function seedRoles() {
        Role::firstOrCreate([
            "name" => User::ROLE_SUPERAMDIN
        ]);

        Role::firstOrCreate([
            "name" => User::ROLE_ADMIN
        ]);
    }


}
