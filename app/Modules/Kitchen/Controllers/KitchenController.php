<?php

namespace App\Modules\Kitchen\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Menus\Models\Menu;
use App\Modules\Reservations\Models\Reservation;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function dashboard(Request $request)
    {
        $reservations = Reservation::all();
        dd($reservations);
        $menuContents = $reservations->pluck('menu_contents');
        $guests = $reservations->pluck('number_of_guests');

        return view('pages.kitchen.dashboard', compact('menuContents', 'guests'));
    }
}