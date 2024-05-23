<?php

namespace App\Modules\Logs\Controllers;


use App\Models\User;
use App\Modules\Logs\Resources\LogResource;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Modules\Logs\Models\Log;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Modules\Logs\Exports\LogsExport;
use App\Modules\Logs\Services\LogService;
use App\Modules\Logs\Resources\LogListResource;

class LogsController extends Controller
{
    private $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    public function index(Request $request)
    {
        $logs = $this->logService->getAll($request);

        return view('pages/logs/index',[
            'is_on_search'=>count($request->all()),
            'logs'=>$logs
        ]);

    }

    public function view($id)
    {

        $log = $this->logService->getByID($id);
        if(is_null($log)) {
            return abort(404);
        }
        return view('pages/logs/show',[
            'log'=>$log
        ]);
    }

}
