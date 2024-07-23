<?php

declare(strict_types=1);

namespace App\Modules\Users\Exports;


use App\Modules\Users\Resources\UsersExportResource;
use App\Modules\Users\Services\UsersService;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection, WithHeadings
{
    private $usersIds;

    public function __construct($users) {
        $this->usersIds = (array) $users;
    }

    public function headings(): array
    {
        return [
            "First Name",
            "Last Name",
            "Email",
            "Phone",

        ];
    }

    public function collection()
    {
        $usersService = new UsersService();
        $users = $usersService->getByIds($this->usersIds);
        return UsersExportResource::collection($users);
    }
}
