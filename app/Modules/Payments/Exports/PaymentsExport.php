<?php

declare(strict_types=1);

namespace App\Modules\Payments\Exports;


use App\Modules\Payments\Resources\PaymentExportResource;
use App\Modules\Payments\Services\PaymentsService;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class PaymentsExport implements FromCollection, WithHeadings
{
    private $paymentsIds;

    public function __construct($payments) {
        $this->paymentsIds = (array) $payments;
    }

    public function headings(): array
    {
        return [
            "Reservation",
            "Client",
            "Date",
            "Value",
            "Notes",


        ];
    }

    public function collection()
    {
        $paymentsService = new PaymentsService();
        $payments = $paymentsService->getByIds($this->paymentsIds);
        return PaymentExportResource::collection($payments);
    }
}
