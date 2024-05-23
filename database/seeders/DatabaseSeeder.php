<?php

namespace Database\Seeders;

use Database\Factories\ClientFactory;
use Database\Factories\PaymentFactory;
use Database\Factories\ReservationFactory;
use Database\Factories\VenueFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


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
        $this->seedClients();
        $this->seedReservations();
        $this->seedPayments();
        DB::table('users')->insert([
            'username' => 'admin',
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'admin@argon.com',
            'password' => bcrypt('secret')
        ]);
    }

    public function seedVenues() {
        (new VenueFactory)->count(10)->create();
    }

    public function seedClients() {
        (new ClientFactory())->count(50)->create();
    }

    public function seedReservations() {
        (new ReservationFactory())->count(50)->create();
    }
    public function seedPayments() {
        (new PaymentFactory())->count(50)->create();
    }
}
