<?php

namespace App\Modules\Kitchen\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Kitchen\Services\KitchenService;
use App\Modules\Menus\Services\MenuService;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    private $kitchenService;
    private $menuService;

    public function __construct(KitchenService $kitchenService, MenuService $menuService)
    {
        $this->kitchenService = $kitchenService;
        $this->menuService = $menuService;
    }

    public function index(Request $request)
    {
        return view('pages.kitchen.index', [
            'orders' => $this->kitchenService->getKitchenOrders($request),
            'menus' => $this->menuService->getAll(request(), false),
            'is_on_search' => count($request->all())
        ]);
    }
   
}