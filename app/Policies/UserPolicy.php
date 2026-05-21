<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return str_ends_with($user->email, '@admin.com') 
            || str_ends_with($user->email, '@nevro-wm.pl')
            || $user->email === 'zbyszeklupikasza@gmail.com';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return str_ends_with($user->email, '@admin.com') 
            || str_ends_with($user->email, '@nevro-wm.pl')
            || $user->email === 'zbyszeklupikasza@gmail.com';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return str_ends_with($user->email, '@admin.com') 
            || str_ends_with($user->email, '@nevro-wm.pl')
            || $user->email === 'zbyszeklupikasza@gmail.com';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return str_ends_with($user->email, '@admin.com') 
            || str_ends_with($user->email, '@nevro-wm.pl')
            || $user->email === 'zbyszeklupikasza@gmail.com';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return str_ends_with($user->email, '@admin.com') 
            || str_ends_with($user->email, '@nevro-wm.pl')
            || $user->email === 'zbyszeklupikasza@gmail.com';
    }
}
