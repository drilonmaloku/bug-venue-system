<?php


namespace App\Modules\Reservations\Exports;

use App\Modules\Reservations\Resources\ReservationGuestListExportResource;
use App\Modules\Reservations\Services\ReservationGuestService;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ReservationGuestsExport implements FromCollection, WithHeadings
{
    private $reservationsGuestIds;

    public function __construct($reservationsGuests) {
        $this->reservationsGuestIds = (array) $reservationsGuests;
    }

    public function headings(): array
    {
        return [
            "Emri",
            "Tavolina",
            "Nr. Personav",
            "Telefoni",
            "Emaili",
            "Statusi",
        ];
    }

    public function collection()
    {
        $reservationGuestService = new ReservationGuestService();
        $reservationsGuests = $reservationGuestService->getByIds($this->reservationsGuestIds);
        return ReservationGuestListExportResource::collection($reservationsGuests);
    }
}
