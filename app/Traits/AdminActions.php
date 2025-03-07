<?php

namespace App\Traits;

use App\Models\User;

trait AdminActions
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->esAdministrador()) {
            return true;
        }

        return null;
    }
}
