<?php

declare(strict_types=1);

namespace App\Modules\Logs\Exports;

use App\Modules\Logs\Resources\LogExportResource;
use App\Modules\Logs\Services\LogService;
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
            "Id",
            "User",
            "Created At",
            "Message",
            "Context",
            "Deletes At",
        ];
    }

    public function collection()
    {
        $logService = new LogService();
        $logs = $logService->getByIds($this->logs);

        return LogExportResource::collection($logs);
    }
}
