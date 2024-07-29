<?php

declare(strict_types=1);

namespace App\Modules\Expenses\Exports;

use App\Modules\Expenses\Resources\ExpenseExportResource;
use App\Modules\Expenses\Services\ExpensesServices;
use App\Modules\Menus\Resources\MenuExportResource;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExpensesExport implements FromCollection, WithHeadings
{
    private $expensesIds;

    public function __construct($expenses) {
        $this->expensesIds = (array) $expenses;
    }

    public function headings(): array
    {
        return [
            "User",
            "Date",
            "Description",
            "Amount",

        ];
    }

    public function collection()
    {
        $expensesService = new ExpensesServices();
        $expenses = $expensesService->getByIds($this->expensesIds);
        return ExpenseExportResource::collection($expenses);
    }
}
