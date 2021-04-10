<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductImagePolicy
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

    public function before($user){
        return $user->isAdmin();
    }
    public function save($user){

    }
    public function delete($user){

    }
    public function deleteAll($user){

    }
}
