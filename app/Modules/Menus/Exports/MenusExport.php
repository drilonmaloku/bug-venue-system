<?php

declare(strict_types=1);

namespace App\Modules\Menus\Exports;

use App\Modules\Menus\Resources\MenuExportResource;
use App\Modules\Menus\Services\MenuService;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class MenusExport implements FromCollection, WithHeadings
{
    private $menuIds;

    public function __construct($menus) {
        $this->menuIds = (array) $menus;
    }

    public function headings(): array
    {
        return [
            "Name",
            "Price",
            "Description",
        ];
    }

    public function collection()
    {
        $menuService = new MenuService();
        $menus = $menuService->getByIds($this->menuIds);
        return MenuExportResource::collection($menus);
    }
}
