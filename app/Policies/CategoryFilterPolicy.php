<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryFilterPolicy
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

    public function deleteCategoryFilter($user){
        return $user->isAdmin();
    }
    public function deleteCategoryFilters($user){
        return $user->isAdmin();
    }

}
