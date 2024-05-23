<?php namespace App\Modules\Clients\Controllers;

use App\Modules\Clients\Exports\ClientsExport;
use App\Modules\Clients\Exports\ContactPersonsExport;
use App\Modules\Clients\Imports\ClientsExcelImport;
use App\Modules\Clients\Requests\AddClientContactPersonRequest;
use App\Modules\Clients\Requests\AddClientRequest;
use App\Modules\Clients\Requests\UpdateClientContactPersonRequest;
use App\Modules\Clients\Requests\UpdateClientRequest;
use App\Modules\Clients\Resources\ClientViewResource;
use App\Modules\Clients\Resources\ContactPersonResource;
use App\Modules\Clients\Services\ClientsContactPersonsService;

use App\Modules\Clients\Services\ClientsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Modules\Logs\Services\LogService;
use App\Modules\Clients\Services\VenuesService;
use App\Modules\Clients\Resources\ClientListResource;

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

            return redirect()->to('clients');

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

}
