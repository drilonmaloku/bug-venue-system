<?php

declare(strict_types=1);

namespace App\Modules\Users\Policies;

use App\Models\User;
use App\Modules\Logs\Models\Log;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function update(User $user): bool
    {
        if (
            auth()->user()->hasRole(User::ROLE_SUPERAMDIN)
            || auth()->user()->id == $user->id
        ) {
            return true;
        }

        return false;
    }
}
