<?php

declare(strict_types=1);

namespace App\Modules\Reservations\Exports;


use App\Modules\Reservations\Resources\ReservationExportResource;
use App\Modules\Reservations\Services\ReservationsService;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ReservationsExport implements FromCollection, WithHeadings
{
    private $reservationsIds;

    public function __construct($reservations) {
        $this->reservationsIds = (array) $reservations;
    }

    public function headings(): array
    {
        return [
            "Venue",
            "Client",
            "Menager",
            "Menu",
            "Menu Price",
            "Date",
            "Reservation Type",
            "Description",
            "Current Payment",
            "Total Payment",
            "Staff Expenses",
        ];
    }

    public function collection()
    {
        $reservationsService = new ReservationsService();
        $reservations = $reservationsService->getByIds($this->reservationsIds);
        return ReservationExportResource::collection($reservations);
    }
}
