<?php

namespace App\Modules\Common\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BackupController extends Controller
{
    private $logService;

    public function __construct(
        LogService $logService
    ) {
        $this->logService = $logService;
    }

     /**
     * Display a listing of the expenses.
     *
     * @param Request $request The HTTP request object.
     * @return \Inertia\Response The Inertia response containing the rendered dashoard view.
     */
    public function index()
    {

        $backups = array_filter(Storage::disk('backups')->files(), function ($item) {
            return strpos($item, '.sql');
        });


        return view('pages/backup/index',[
            'backups'=>$backups,
        ]);


    }

    /**
     * Display a listing of the expenses.
     *
     */
    public function createDatabaseBackup()
    {
        $command = 'mysqldump --user='.env('DB_USERNAME').' --password='.env('DB_PASSWORD').' --host=localhost '.env('DB_DATABASE').' > "'.storage_path().'/app/backups/backup'.time().'.sql"';
        exec("(".$command.") 2>&1", $output, $result);
        $this->logService->log([
            'message' => 'Created DB Backup',
            'context' => Log::LOG_CONTEXT_COMMON,
            'ttl'=> Log::LOG_TTL_THREE_MONTHS,
        ]);
        return redirect()->route('common.backup');
    }

    public function downloadDatabaseBackup($file){
        $this->logService->log([
            'message' => "Downloading DB Backup: $file",
            'context' => Log::LOG_CONTEXT_COMMON,
            'ttl'=> Log::LOG_TTL_THREE_MONTHS,
        ]);
        return response()->download(storage_path('/app/backups/'.$file));
    }

    public function deleteDatabaseBackup($file){
        unlink(storage_path('app/backups/'.$file));
        $this->logService->log([
            'message' => "Removing DB Backup: $file",
            'context' => Log::LOG_CONTEXT_COMMON,
            'ttl'=> Log::LOG_TTL_THREE_MONTHS,
        ]);
        return redirect()->route('common.backup');

    }
}
