<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CouponPolicy
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
    public function manage($user){
        
    }

    public function save($user){
        
    }

    public function create($user){
        
    }

    public function edit($user){
        
    }

    public function update($user){
        
    }
    public function delete($user){
        
    }
    public function deleteAll($user){
        
    }
}
