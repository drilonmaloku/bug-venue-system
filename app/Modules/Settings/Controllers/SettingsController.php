<?php namespace App\Modules\Settings\Controllers;

use App\Modules\Clients\Services\ClientsService;
use App\Modules\Settings\Models\LocationSettings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Logs\Services\LogService;

class SettingsController extends Controller
{
    private $clientsService;
    private $logService;

    public function __construct(
        ClientsService $clientsService,
        LogService $logService
    )
    {
        $this->clientsService = $clientsService;
        $this->logService = $logService;
    }

    public function index(Request $request)
    {

        $location = auth()->user()->getCurrentLocation();
        $locationSettings = $location->locationSettings;

        $contractContent = json_decode($locationSettings->settings, true);

        return view('pages.contracts.edit', [
            'location_contract' => $contractContent['contract']
        ]);
    }

    public function save(Request $request)
    {
        $location = auth()->user()->getCurrentLocation();
        $locationSettings = $location->locationSettings;

        $locationSettings->settings = json_encode([
            'contract' => $request->input('contractContent')
        ]);
        $locationSettings->save();
        return redirect()->to('/settings');
    }

}
