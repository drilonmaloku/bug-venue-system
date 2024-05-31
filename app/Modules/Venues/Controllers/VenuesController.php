<?php namespace App\Modules\Venues\Controllers;

use App\Modules\Clients\Exports\ClientsExport;
use App\Modules\Clients\Requests\UpdateClientContactPersonRequest;
use App\Modules\Clients\Requests\UpdateClientRequest;
use App\Modules\Clients\Requests\UpdateVenueRequest;
use App\Modules\Clients\Resources\ClientViewResource;


use App\Modules\Venues\Requests\AddVenueRequest;
use App\Modules\Venues\Services\VenuesService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Modules\Logs\Services\LogService;
use App\Modules\Clients\Resources\ClientListResource;
use RealRashid\SweetAlert\Facades\Alert;

class VenuesController extends Controller
{
    private $venuesService;
    private $logService;

    public function __construct(
        VenuesService $venuesService,
        LogService $logService
    )
    {
        $this->venuesService = $venuesService;
        $this->logService = $logService;
    }

    public function index(Request $request)
    {
        $venues = $this->venuesService->getAll($request);
        if(session('success_message')){
            Alert::success('Success!', session('success_message'));
        }
        return view('pages/venues/index',[
            'venues'=>$venues,
             'is_on_search'=>count($request->all())
        ]);

    }

    public function create()
    {
        return view('pages/venues/create');
    }

    public function view($id)
    {
        $venue = $this->venuesService->getByID($id);
        if(is_null($venue)) {
            return abort(404);
        }
        return view('pages/venues/show',[
            'venue'=>$venue
        ]);
    }

    public function store(Request $request) {
        $venue = $this->venuesService->store($request);

        return redirect()->to('venues')->withSuccessMessage('Salla u krijua me sukses');
        try {


        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function edit($id)
    {
        $venue = $this->venuesService->getByID($id);
        if(is_null($venue)) {
            return abort(404);
        }
        return view('pages/venues/edit',[
            'venue'=>$venue
        ]);
    }

    public function update(Request $request,$id) {
        $venue = $this->venuesService->getByID($id);

        if(is_null($venue)) {
            return abort(404);
        }

        try {

            $venue = $this->venuesService->update($request,$venue);

            return redirect()->to('venues')->withSuccessMessage('Perduruesi u be update me sukses');

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function delete($id){
        $venue = $this->venuesService->getByID($id);
        if(is_null($venue)) {
            abort('Venue not found',404);
        }
        try {
            $clientDeleted = $this->venuesService->delete($venue);

            if($clientDeleted) {
                return redirect()->to('venues')->withSuccessMessage('Perduruesi u fshi me sukses');
            }
            return redirect()->to('venues');

        } catch (\Exception $e) {
            return redirect()->to('venues');
        }
    }


}
