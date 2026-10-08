<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\PtSession;
use App\Models\User;

class PtSessionPolicy
{
    public function view(User $user, PtSession $ptSession): bool
    {
        if ($user->isTrainer()) {
            return $user->id === $ptSession->trainer_id;
        }
        
        if ($user->isMember()) {
            return $user->id === $ptSession->member_id;
        }

        return false;
    }
}
