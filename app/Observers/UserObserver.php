<?php

namespace App\Observers;

use App\Actions\CreateTeam;
use App\Models\Team;
use App\Models\User;

class UserObserver
{
    public function __construct(protected CreateTeam $createTeam)
    {
        //
    }

    public function created(User $user)
    {
        $this->createTeam->handle($user, [
            'name' => $user->name
        ]);
    }

    public function deleting(User $user)
    {
        $user->teams()->detach();
    }
}
