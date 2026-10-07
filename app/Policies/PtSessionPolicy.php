<?php

namespace App\Policies;

use App\Models\PtSession;
use App\Models\User;

class PtSessionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PtSession $session): bool
    {
        return $user->id === $session->member_id
            || $user->id === $session->trainer_id
            || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isMember() || $user->isAdmin();
    }

    /**
     * Determine whether the user can update (reschedule) the model.
     */
    public function update(User $user, PtSession $session): bool
    {
        return $user->id === $session->member_id;
    }

    /**
     * Determine whether the user can cancel the model.
     */
    public function cancel(User $user, PtSession $session): bool
    {
        return $user->id === $session->member_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PtSession $session): bool
    {
        return $user->id === $session->member_id;
    }
}
