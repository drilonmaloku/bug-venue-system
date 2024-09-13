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
        $startDate = $request->input('starting_date');
        $endDate = $request->input('ending_date');
        $reportsData = $this->reportsService->generateGeneralReport($startDate,$endDate);
        return view('pages/reports/generated',
        [
            'reportsData' =>$reportsData
        ]);
    }

}
