<?php namespace App\Modules\Clients\Controllers;


use App\Modules\Clients\Services\ClientsService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Logs\Services\LogService;
use RealRashid\SweetAlert\Facades\Alert;

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

        if(session('success_message')){
            Alert::success('Success!', session('success_message'));
        }

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

            return redirect()->to('clients')->withSuccessMessage('Klienti u be update me sukses');

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

}
