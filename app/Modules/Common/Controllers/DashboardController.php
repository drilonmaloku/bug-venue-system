<?php

namespace App\Modules\Common\Controllers;

use App\Modules\Menus\Models\Menu;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Venues\Models\Venue;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
            // Use the venue's ID to get the color
            $color = isset($colors[$reservation->venue_id]) ? $colors[$reservation->venue_id] : '#000000'; // Default to black

            return [
                'id' => $reservation->id,
                'title' => $reservation->client->name . ',' . $reservation->venue->name,
                'start' => $reservation->date,
                'end' => $reservation->date,
                'color' => $color,
            ];
        });

        return view('pages.dashboard.index', [
            'venues' => $venues,
            'events' => $events->toArray(),
            'menus' => Menu::all(),
        ]);
    }


public function fetchEvents(Request $request)
{
    $start = $request->input('start');
    $end = $request->input('end');

    // Convert start and end dates to Carbon instances (assuming they are ISO 8601 strings)
    $start = \Carbon\Carbon::parse($start);
    $end = \Carbon\Carbon::parse($end);


    $colors = [
        1 => '#ff6961', // Coral
        2 => '#77dd77', // Pastel Green
        3 => '#aec6cf', // Light Blue
        4 => '#f49ac2', // Orchid Pink
        5 => '#f0e68c', // Khaki
        6 => '#ffb347', // Orange
        // Add more colors as needed
    ];

    $reservations = Reservation::with('venue')
                                ->whereBetween('date', [$start, $end])
                                ->get();

    $events = $reservations->map(function ($reservation)  use ($colors){

        $color = isset($colors[$reservation->venue_id]) ? $colors[$reservation->venue_id] : '#000000'; // Default to black
        return [
            'id' => $reservation->id,
            'title' => $reservation->client->name . ',' . $reservation->venue->name,
            'start' => $reservation->date,
            'end' => $reservation->date,
            'color' => $color,

        ];
    });

    return response()->json($events);
}





}
