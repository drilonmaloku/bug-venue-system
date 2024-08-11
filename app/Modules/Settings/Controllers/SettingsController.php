<?php namespace App\Modules\Settings\Controllers;


use App\Modules\Clients\Exports\ClientsExport;
use App\Modules\Clients\Services\ClientsService;
use App\Modules\Logs\Models\Log;
use App\Modules\Settings\Models\LocationSettings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Logs\Services\LogService;
use Maatwebsite\Excel\Facades\Excel;

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
        // Fetch the location settings
        $locationSettings = LocationSettings::find(1);


        // Fetch the contract content (assuming it's stored as JSON)
        $contractContent = json_decode($locationSettings->settings, true);

        return view('pages.contracts.edit', [
            'location_contract' => $contractContent['contract']
        ]);
    }

    public function save(Request $request)
    {
        $locationSettings = LocationSettings::find(1);
        $locationSettings->settings = json_encode([
            'contract' => $request->input('contractContent')
        ]);
        $locationSettings->save();
        return redirect()->to('/settings');
    }

    public function view($id)
    {
        $client = $this->clientsService->getByID($id);
        if(is_null($client)) {
            return abort(404);
        }
        return view('pages/clients/show',[
            'client'=>$client
        ]);
    }

    public function edit($id)
    {
        $client = $this->clientsService->getByID($id);
        if(is_null($client)) {
            return abort(404);
        }
        return view('pages/clients/edit',[
            'client'=>$client
        ]);
    }

    public function update(Request $request,$id) {
        $client = $this->clientsService->getByID($id);

        if(is_null($client)) {
            return abort(404);
        }

        try {
            $client = $this->clientsService->update($request,$client);
            alert()->success(__('clients.alert.success'))->autoclose(2000);
            return redirect()->to('clients');
        } catch (\Exception $e) {
            alert()->success(__('clients.alert.error'))->autoclose(2000);
            return redirect()->to('clients');
        }
    }

    public function export(Request $request)
    {
        $clients = null;

        if($request->has('ids')) {
            $clients = explode(',', $request->input('ids'));
        }
        $this->logService->log([
            'message' => 'Clients are being exported to Excel',
            'context' => Log::LOG_CONTEXT_CLIENTS,
            'ttl'=> Log::LOG_TTL_THREE_MONTHS,
        ]);
        return Excel::download(new ClientsExport($clients), "clients-export.xlsx");
    }

}
