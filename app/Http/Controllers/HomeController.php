<?php

namespace App\Http\Controllers;

use App\Modules\Menus\Models\Menu;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Reservations\Services\ReservationsService;
use App\Modules\Venues\Models\Venue;
use DateTime;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $reservationsService;


    public function __construct(
        ReservationsService $reservationsService
    )
    {
        $this->reservationsService = $reservationsService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
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
                'title' => $reservation->description, // Use venue name as event title
                'start' => $formattedDate,
                'end' => $formattedDate,
                'color' => $color,
            ];
        });

        return view('pages.dashboard.index', [
            'events' => $events->toArray(),
            'venues' => $venues,
            'menus' => Menu::all(),
        ]);
    }
}
