<?php

declare(strict_types=1);

namespace App\Modules\Users\Exports;

use App\Models\User;
use App\Modules\Users\Resources\UserExportResource;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Modules\Users\Resources\UserListResource;

class UsersExport implements FromCollection, WithHeadings
{
    protected $usersIds;
    protected $role;

    public function __construct($users, $role = null) {
        $this->usersIds = (array) $users;
        $this->role = (string) $role;
    }

    public function headings(): array
    {
        return [
            "Id",
            "Name",
            "Email",
            "Username",
            "Phone",
            "Enabled",
        ];
    }

    public function collection()
    {
        $query = User::whereHas("roles", function ($query) {
            $query->whereIn("name", ["admin","super-admin"]);
        });
        if(!empty($this->usersIds)){
            $query->whereIn("id", $this->usersIds);
        }

        $users =  $query->get();

        return UserExportResource::collection($users);
    }
}
