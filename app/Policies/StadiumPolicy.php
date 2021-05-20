<?php

namespace App\Policies;

use App\Models\Stadium;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StadiumPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Stadium  $stadium
     * @return mixed
     */
    public function view(User $user, Stadium $stadium)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Stadium  $stadium
     * @return mixed
     */
    public function update(User $user, Stadium $stadium)
    {
        return $stadium->user_id === $user->id || $user->username === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Stadium  $stadium
     * @return mixed
     */
    public function delete(User $user, Stadium $stadium)
    {
        return $stadium->user_id === $user->id || $user->username === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Stadium  $stadium
     * @return mixed
     */
    public function restore(User $user, Stadium $stadium)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Stadium  $stadium
     * @return mixed
     */
    public function forceDelete(User $user, Stadium $stadium)
    {
        //
    }
}
