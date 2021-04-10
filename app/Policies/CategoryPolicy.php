<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
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
    
    public function create($user){
        return $user->isAdmin();
    }
    public function save($user){
        return $user->isAdmin();
    }
    public function update($user){
        return $user->isAdmin();
    }
    public function edit($user){
        return $user->isAdmin();
    }
    public function delete($user){
        return $user->isAdmin();
    }
    public function deleteAll($user){
        return $user->isAdmin();
    }
}
