<?php namespace App\Modules\Logs\Services;

use Illuminate\Http\Request;
use App\Modules\Logs\Models\Log;
use Illuminate\Support\Facades\DB;
use App\Modules\Logs\Resources\LogResource;

class LogService
{
    /**
     * Filter and paginate logs based on request parameters.
     *
     * @param Request $request
     * @return array
 */
    public function filterLogs($request) 
    {
        $logsQuery = (new Log)->query();
        $search = data_get($request, "search");
        $logsQuery->where("message", "like", "%" . $search . "%");

        if ($request->has("start_date") && $request->has("end_date")) {
            $logsQuery->whereBetween("created_at", [
                data_get($request, "start_date"),
                data_get($request, "end_date")
            ]);
        } elseif (! is_null(data_get($request, "end_date"))) {
            $logsQuery->where("created_at", "<=", data_get($request, "end_date"));
        } elseif (! is_null(data_get($request, "start_date"))) {
            $logsQuery->where("created_at", ">=", data_get($request, "start_date"));
        }

        if (!empty($request->input("user"))) {
            $logsQuery->where("user_id", data_get($request, "user"));
        }

        $logsQuery->orderBy(
            data_get($request, "order_by") ?? "created_at",
            data_get($request, "sort_direction") ?? "desc"
        );

        if (!empty($request->input("context"))) {
            $logsQuery->where("context", data_get($request, "context"));
        }

        $resultIds = $logsQuery->get("id")->toArray();

        $logs = $logsQuery->paginate(
                data_get($request, "per_page") ?? 50
            );

        $logs->appends($request->all());

        return [
            "logs" => $logs,
            "resultIds" => $resultIds
        ];
    }

   /**
     * Get the list of logs.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAll(Request $request){
        $perPage = $request->has('per_page') ? $request->input('per_page') : 25;
        $query = Log::query();
        if(!empty($request->input('user_id'))) {
            $query->where('user_id',$request->input('user_id'));
        }
        if(!empty($request->input('date'))) {
            $query->whereDate('created_at', $request->input('date'));
        }
        if(!empty($request->input('search'))) {
            $query->where('message','like', '%' . $request->input('search') . '%');
        }
        return $query->orderBy('id', 'desc')->with('user')->paginate($perPage);

    }

    /**
     * Get Log by ID
     * @param bool $id
     **/
    public function getByID($id){
        return Log::with('user')->find($id);
    }

    /**
     * Store a new log entry.
     *
     * @param Request $request
     * @return Log
     */
    public function store($request) : Log
    {
        return (new Log)->create([
            "user_id" => auth()->user()->id,
            "message" => data_get($request, "message"),
            "context" => data_get($request, "context"),
            "previous_data" => data_get($request, "previous_data"),
            "updated_data" => data_get($request, "updated_data"),
            "ttl" => data_get($request, "ttl"),
            "keep_alive" => data_get($request, "keep_alive"),
            "deletes_at" => $this->getLogExpiryTime(
                data_get($request, "ttl")),
        ]);
    }

    /**
     * Update the TTL Keep Alive for an existing log.
     *
     * @param Log $log
     * @return bool
     */
    public function keepLogAlive($log){
        $log->keepAlive = Log::LOG_TTL_KEEP_ALIVE;
        return $log->save();
    }

    /**
     * Update the TTL Keep Alive for multiple logs.
     *
     * @param Request $request
     * @return void
     */
    public function keepMultipleLogsAlive($request) {

        $postIds = $request->input("logs");

        (new Log)->whereIn("id", $postIds)->update([
                "keep_alive" => Log::LOG_TTL_KEEP_ALIVE,
            ]);
    }

    /**
     * Get the expiration time for a log based on its TTL.
     *
     * @param int $ttl
     * @return \Illuminate\Support\Carbon|null
     */
    public function getLogExpiryTime($ttl){
        switch ($ttl) {
            case Log::LOG_TTL_ONE_YEAR:
                return now()->addMonths(12);
                break;
            case Log::LOG_TTL_SIX_MONTHS:
                return now()->addMonths(6);
                break;
            case LOG::LOG_TTL_THREE_MONTHS:
                return now()->addMonths(3);
                break;
            default:
                return null;
                break;
        }
    }

    /**
     * Get a single log resource.
     *
     * @param Log $log
     * @return LogResource
     */
    public function getSingleLog(Log $log) {
        $logResource = new LogResource($log);

        return $logResource;
    }

    /**
     * Log an event.
     *
     * @param array $data
     * @return Log
     */
    public function log($data)
    {
        return (new Log)->create([
            "user_id" => auth()->user()->id,
            "message" => $data['message'],
            "context" => $data['context'],
            "previous_data" => $data['previous_data'] ?? null,
            "updated_data" => $data['updated_data'] ?? null,
            "ttl" => $data['ttl'],
            "keep_alive" => $data['keep_alive'] ?? false,
            "deletes_at" => $this->getLogExpiryTime($data['ttl']),
        ]);
    }
}
