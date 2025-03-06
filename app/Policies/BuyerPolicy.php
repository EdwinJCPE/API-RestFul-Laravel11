<?php

namespace App\Policies;

use App\Models\Buyer;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BuyerPolicy
{
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
