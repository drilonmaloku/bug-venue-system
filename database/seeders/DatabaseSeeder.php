<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\ClientFactory;
use Database\Factories\LogsFactory;
use Database\Factories\PaymentFactory;
use Database\Factories\ReservationFactory;
use Database\Factories\UserFactory;
use Database\Factories\VenueFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->seedVenues();
        $this->seedRoles();
        $this->seedClients();
        $this->seedUsers();
        $this->seedReservations();
        $this->seedPayments();
        $this->seedLogs();
    }

    public function seedVenues() {
        (new VenueFactory)->count(10)->create();
    }

    public function seedClients() {
        (new ClientFactory())->count(50)->create();
    }

    public function seedUsers() {
        $users = (new UserFactory)->count(10)->create();

        $users->each(function ($user) {
            $user->assignRole(User::ROLE_ADMIN);
        });

        $user = User::create([
            'username' => 'admin',
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'admin@argon.com',
            'password' => bcrypt('secret')
        ]);
        $user->assignRole(User::ROLE_SUPERAMDIN);
    }

    public function seedReservations() {
        (new ReservationFactory())->count(50)->create();
    }

    public function seedPayments() {
        (new PaymentFactory())->count(50)->create();
    }

    public function seedLogs() {
        (new LogsFactory())->count(50)->create();
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
