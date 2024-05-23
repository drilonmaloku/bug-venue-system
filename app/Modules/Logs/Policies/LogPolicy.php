<?php

declare(strict_types=1);

namespace App\Modules\Logs\Policies;

use App\Models\User;
use App\Modules\Logs\Models\Log;
use Illuminate\Auth\Access\Response;

class LogPolicy
{
    public function view(User $user, Log $log): bool
    {
        if (
            auth()->user()->hasRole(User::ROLE_SUPERAMDIN)
            || auth()->user()->id == $log->user_id
            || (
                ! $log->user->hasRole(User::ROLE_SUPERAMDIN)
                && auth()->user()->hasRole("admin")
            )
        ) {
            return true;
        }

        return false;
    }
}
