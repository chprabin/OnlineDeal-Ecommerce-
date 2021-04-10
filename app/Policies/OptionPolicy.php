<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OptionPolicy
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
    public function save($user){
        return $user->isAdmin();
    }
    public function delete($user){
        return $user->isAdmin();
    }
    public function deleteAll($user){
        return $user->isAdmin();
    }
}
