<?php

namespace App\Policies;

use App\Models\Buyer;
use App\Models\User;
use App\Traits\AdminActions;
use Illuminate\Auth\Access\Response;

class BuyerPolicy
{
    use AdminActions;

    // public function before(User $user, string $ability): bool|null
    // {
    //     if ($user->esAdministrador()) {
    //         return true;
    //     }

    //     return null;
    // }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Buyer $buyer): bool
    {
        // dd($user, $buyer);
        return $user->id === $buyer->id;
    }

    /**
     * Determine whether the user can purchase something.
     */
    public function purchase(User $user, Buyer $buyer): bool
    {
        return $user->id === $buyer->id;
    }
}
