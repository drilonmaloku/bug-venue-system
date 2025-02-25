<?php namespace App\Modules\Clients\Controllers;


use App\Modules\Clients\Exports\ClientsExport;
use App\Modules\Clients\Services\ClientsService;
use App\Modules\Logs\Models\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Clients\Imports\ClientsImport;
use App\Modules\Clients\Models\Client;
use App\Modules\Logs\Services\LogService;
use Maatwebsite\Excel\Facades\Excel;

class ClientsController extends Controller
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
        $clients = $this->clientsService->getAll($request);

        return view('pages/clients/index',[
            'is_on_search'=>count($request->all()),
            'clients'=>$clients
        ]);
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
    public function import(Request $request)
{
    // Validate the uploaded file
    $request->validate([
        'file' => 'required|mimes:xlsx,xls',
    ], [
        'file.required' => 'Please select a file to import',
        'file.mimes' => 'The file must be an Excel file (xlsx or xls)',
    ]);

    try {
        // Get the uploaded file
        $file = $request->file('file');
        
        // Process the Excel file
        Excel::import(new ClientsImport, $file);
        
        // Log the successful import
        $this->logService->log([
            'message' => 'Clients were successfully imported from Excel',
            'context' => Log::LOG_CONTEXT_CLIENTS,
            'ttl'=> Log::LOG_TTL_THREE_MONTHS,
        ]);
        
        alert()->success(__('clients.alert.import_success'))->autoclose(2000);
        return redirect()->back();
    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        // Handle validation errors from the Excel import
        $failures = $e->failures();
        $errorMessage = 'Import failed. Please check your Excel file format.';
        
        alert()->error($errorMessage)->autoclose(3000);
        return redirect()->back();
    } catch (\Exception $e) {
        // Handle other unexpected errors
        $this->logService->log([
            'message' => 'Client import failed: ' . $e->getMessage(),
            'context' => Log::LOG_CONTEXT_CLIENTS,
            'ttl'=> Log::LOG_TTL_THREE_MONTHS,
            'error' => true,
        ]);
        
        alert()->error(__('clients.alert.import_error'))->autoclose(2000);
        return redirect()->back();
    }
}
    public function getClients(Request $request)
        {
            $search = $request->input('term');
            $clients = Client::where('name','LIKE', '%' . $search . '%')
                ->select('id', 'name') // Select only the fields needed
                ->get();

            return response()->json($clients);
        }

}
