<?php

namespace App\Modules\Common\Controllers;


use App\Modules\Menus\Models\Menu;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Venues\Models\Venue;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
                'title' => $this->formatEventTitle($reservation),
                'start' => $reservation->date,
                'end' => $reservation->date,
                'color' => $color,
            ];
        });

        if(auth()->user()->hasRole('kitchen')) {
          return $this->getKitchenDashboard();
        }
        return view('pages.dashboard.index', [
            'venues' => $venues,
            'events' => $events->toArray(),
            'menus' => Menu::all(),
        ]);
    }


    public function getKitchenDashboard(){
        $today = Carbon::today();
        $nextWeek = Carbon::today()->addWeeks(2);

        $reservations = Reservation::with('menu')
            ->whereDate('date', $today) // Today's reservations
            ->orWhereBetween('date', [$today->copy()->addDay(), $nextWeek]) // Next 7 days
            ->get();

        return view('pages.dashboard.kitchen', [
            'menus' => Menu::all(),
            'reservations' => $reservations,
        ]);
    }

    public function getUsersDashboard(){
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
                'title' => $this->formatEventTitle($reservation),
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
        $venueId = $request->input('venue');

        // Convert start and end dates to Carbon instances
        try {
            $start = \Carbon\Carbon::parse($start);
            $end = \Carbon\Carbon::parse($end);
        } catch (\Exception $e) {
            dd($e);
            return response()->json(['error' => 'Invalid date format.'], 400);
        }




        $colors = [
            1 => '#ff6961', // Coral
            2 => '#77dd77', // Pastel Green
            3 => '#aec6cf', // Light Blue
            4 => '#f49ac2', // Orchid Pink
            5 => '#f0e68c', // Khaki
            6 => '#ffb347', // Orange
            // Add more colors as needed
        ];



        $query = Reservation::with('venue')->whereBetween('date', [$start, $end]);

        if($venueId) {
            $query->where('venue_id', $venueId);
        }
        $reservations = $query->get();

        $events = $reservations->map(function ($reservation)  use ($colors){

            $color = isset($colors[$reservation->venue_id]) ? $colors[$reservation->venue_id] : '#000000'; // Default to black
            return [
                'id' => $reservation->id,
                'title' => $this->formatEventTitle($reservation),
                'start' => $reservation->date,
                'end' => $reservation->date,
                'color' => $color,

            ];
        });

        return response()->json($events);
    }

    private function formatEventTitle($reservation) {
        $template = auth()->user()->userSettings->event_title_template ?? '{client_name}, {venue_name}, {menu}, {menu_price}';

        $placeholders = [
            '{client_name}' => $reservation->client->name ?? '', 
            '{venue_name}' => $reservation->venue->name ?? '', 
            '{menu}' => $reservation->menu->name ?? '', 
            '{menu_price}' => $reservation->menu ? number_format($reservation->menu->price, 2) : '' 
        ];

        return strtr($template, $placeholders);
    }

}
