<?php namespace App\Modules\Reports\Controllers;

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
use App\Modules\Menus\Services\MenuService;
use App\Modules\Reports\Services\ReportsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Modules\Logs\Services\LogService;
use App\Modules\Clients\Services\VenuesService;
use App\Modules\Clients\Resources\ClientListResource;

class ReportsController extends Controller
{
    private $reportsService;
    private $logService;

    public function __construct(
        ReportsService $reportsService,
        LogService $logService
    )
    {
        $this->reportsService = $reportsService;
        $this->logService = $logService;
    }

    public function index()
    {
        return view('pages/reports/index');
    }



    public function generate(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $reportsData = $this->reportsService->generateGeneralReport($startDate,$endDate);
        return view('pages/reports/generated',
        [
            'reportsData' =>$reportsData
        ]);
    }

    public function store(Request $request) {
        $menu = $this->menuService->store($request);

        return redirect()->to('menus');
    }

    public function view($id)
    {
        $menu = $this->menuService->getByID($id);
        if(is_null($menu)) {
            return abort(404);
        }
        return view('pages/menus/show',[
            'menu'=>$menu
        ]);
    }

    public function edit($id)
    {
        $menu = $this->menuService->getByID($id);
        if(is_null($menu)) {
            return abort(404);
        }
        return view('pages/menus/edit',[
            'menu'=>$menu
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
