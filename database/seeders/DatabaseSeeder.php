<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Reservations\Models\Reservation;
use Database\Factories\ClientFactory;
use Database\Factories\ExpenseFactory;
use Database\Factories\LogsFactory;
use Database\Factories\MenuFactory;
use Database\Factories\PaymentFactory;
use Database\Factories\ReservationFactory;
use Database\Factories\SupportTicketsFactory;
use Database\Factories\UserFactory;
use Database\Factories\VenueFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

        $this->seedRoles();
        $this->seedVenues();
        $this->seedMenus();
        $this->seedClients();
        $this->seedUsers();
        $this->seedReservations();
        $this->seedTickets();
        $this->seedPayments();
        $this->seedExpenses();

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
            'email' => 'drilon.maloku13@gmail.com',
            'password' => bcrypt('loniloni13')
        ]);
        $user->assignRole(User::SYSTEM_ADMIN);
    }

    public function seedMenus() {
        (new MenuFactory())->count(10)->create();
    }
    public function seedReservations() {
        Reservation::withoutEvents(function () {
            (new ReservationFactory())->count(50)->create();
        });
 
    }

    public function seedTickets() {
        Reservation::withoutEvents(function () {
            (new SupportTicketsFactory())->count(50)->create();
        });
 
    }

    
    public function seedPayments() {
        (new PaymentFactory())->count(50)->create();
    }

    public function seedExpenses() {
        (new ExpenseFactory())->count(50)->create();
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
        Role::firstOrCreate([
            "name" => User::ROLE_MANAGER
        ]);
        Role::firstOrCreate([
            "name" => User::SYSTEM_ADMIN
        ]);
    }


}
