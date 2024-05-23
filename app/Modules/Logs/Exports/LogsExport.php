<?php

declare(strict_types=1);

namespace App\Modules\Logs\Exports;

use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Resources\LogExportResource;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class LogsExport implements FromCollection, WithHeadings
{
    protected $logs;

    public function __construct($logs) {
        $this->logs = (array) $logs;
    }

    public function headings(): array
    {
        return [
            "id",
            "user",
            "created_at",
            "message",
            "context",
            "deletes_at",
        ];
    }

    public function collection()
    {
        if (empty($this->logs)) {
            $logs = (new Log)->all();
        } 
        else {
            $logs = (new Log)->whereIn("id", $this->logs)->get();
        }

        return LogExportResource::collection($logs);
    }
}
