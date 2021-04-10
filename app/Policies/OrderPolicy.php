<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function manage($user){
        return $user->isAdmin();
    }
    public function partialUpdate($user){
        return $user->isAdmin();
    }
    public function details($user){
        return $user->isAdmin();
    }

    public function update($user){
        return $user->isAdmin();
    }
}
