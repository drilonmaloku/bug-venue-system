<?php namespace App\Modules\Common\Controllers;



use App\Modules\Menus\Models\Menu;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Venues\Models\Venue;
use App\Http\Controllers\Controller;




class DashboardController extends Controller
{

    public function index()
    {
        $reservations = Reservation::with('venue')->get();

        $venues = Venue::all(); // Assuming you have a Venue model

        $colors = [
            1 => '#ff6961', // Coral
            2 => '#77dd77', // Pastel Green
            3 => '#aec6cf', // Light Blue
            4 => '#f49ac2', // Orchid Pink
            5 => '#f0e68c', // Khaki
            6 => '#ffb347', // Orange
            // Add more colors as needed
        ];

        $events = $reservations->map(function ($reservation) use ($colors) {
            $carbonDate = \Carbon\Carbon::createFromFormat('d-m-Y', $reservation->date);
            $formattedDate = $carbonDate->format('Y-m-d');

            // Use the venue's ID to get the color
            $color = isset($colors[$reservation->venue_id]) ? $colors[$reservation->venue_id] : '#000000'; // Default to black

            return [
                'title' => $reservation->client->name.','.$reservation->venue->name,
                'start' => $formattedDate,
                'end' => $formattedDate,
                'color' => $color,
            ];
        });

        return view('pages.dashboard.index', [
            'venues' => $venues,
            'events' => $events->toArray(),
            'menus' =>Menu::all(),
        ]);
    }

}
