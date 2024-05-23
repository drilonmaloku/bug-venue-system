<?php

declare(strict_types=1);

namespace App\Modules\Clients\Exports;

use App\Modules\Clients\Resources\ClientExportResource;
use App\Modules\Clients\Services\VenuesService;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use function Spatie\MediaLibrary\MediaCollections\getByIds;

class ClientsExport implements FromCollection, WithHeadings
{
    private $clientIds;

    public function __construct($clients) {
        $this->clientIds = (array) $clients;
    }

    public function headings(): array
    {
        return [
            "Name",
            "Email",
            "Phone",
            "Additional Phone",
        ];
    }

    public function collection()
    {
        $clientService = new VenuesService();
        $clients = $clientService->getByIds($this->clientIds);
        return ClientExportResource::collection($clients);
    }
}
