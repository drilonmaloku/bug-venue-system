<?php

namespace App\Modules\Logs\Controllers;


use App\Modules\Users\Services\UsersService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Modules\Logs\Services\LogService;


class LogsController extends Controller
{
    private $logService;
    private $usersService;

    public function __construct(
        LogService $logService,
        UsersService $usersService
    )
    {
        $this->logService = $logService;
        $this->usersService = $usersService;
    }

    public function index(Request $request)
    {
        $logs = $this->logService->getAll($request);

        return view('pages/logs/index',[
            'is_on_search'=>count($request->all()),
            'logs'=>$logs,
            'users'=>$this->usersService->getAll(null,true)
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
