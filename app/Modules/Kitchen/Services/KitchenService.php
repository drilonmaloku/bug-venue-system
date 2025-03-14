<?php

namespace App\Modules\Kitchen\Services;

use App\Modules\Reservations\Models\Reservation;
use Illuminate\Http\Request;

class KitchenService
{
    public function getKitchenOrders(Request $request)
    {
        $perPage = $request->has('per_page') ? $request->input('per_page') : 25;
        
        $query = Reservation::with('menu')
            ->select('date', 'menu_contents', 'number_of_guests', 'menu_id', 'id');

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->whereHas('menu', function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%');
            })
            ->orWhere('menu_contents', 'LIKE', '%' . $searchTerm . '%');
        }

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        if ($request->filled('menu')) {
            $query->where('menu_id', $request->menu);
        }

        return $query->orderBy('date', 'desc')->paginate($perPage);
    }
} 