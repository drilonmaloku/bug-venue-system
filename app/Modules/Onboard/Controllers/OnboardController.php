<?php namespace App\Modules\Onboard\Controllers;


use App\Modules\Clients\Exports\ClientsExport;

use App\Modules\Common\Utils\Utils;
use App\Modules\Logs\Models\Log;
use App\Modules\Menus\Services\MenuService;
use App\Modules\Venues\Services\VenuesService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class OnboardController extends Controller
{
    private $venuesService;
    private $menuService;

    public function __construct(
        VenuesService $venuesService,
        MenuService $menuService
    )
    {
        $this->venuesService = $venuesService;
        $this->menuService = $menuService;
    }

    public function index(Request $request)
    {
        return view('pages/onboard/index');
    }

    public function store(Request $request)
    {

        $utils = app()->make(Utils::class);
        $menuData = $utils->removePrefix($request->all(), 'menu_');
        $venueData = $utils->removePrefix($request->all(), 'venue_');

        // Create new Request instances with cleaned data
        $cleanMenuRequest = new Request($menuData);
        $cleanVenueRequest = new Request($venueData);

        // Pass the new request objects to your services
        $menu = $this->menuService->store($cleanMenuRequest);
        $venue = $this->venuesService->store($cleanVenueRequest);

        return redirect()->to('dashboard')
            ->withSuccessMessage('Onboard success');
    }

}
