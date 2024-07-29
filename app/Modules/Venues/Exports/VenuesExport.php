<?php

declare(strict_types=1);

namespace App\Modules\Venues\Exports;


use App\Modules\Venues\Resources\VenueExportResource;
use App\Modules\Venues\Services\VenuesService;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class VenuesExport implements FromCollection, WithHeadings
{
    private $venuesIds;

    public function __construct($venues) {
        $this->venuesIds = (array) $venues;
    }

    public function headings(): array
    {
        return [
            "Name",
            "Description",
            "Capacity",
        ];
    }

    public function collection()
    {
        $venuesService = new VenuesService();
        $venues = $venuesService->getByIds($this->venuesIds);
        return VenueExportResource::collection($venues);
    }
}
