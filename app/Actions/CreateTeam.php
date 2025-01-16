<?php

namespace App\Actions;

use App\Models\Team;
use App\Models\User;

class CreateTeam
{
    public function handle(User $user, array $data)
    {
        $user->teams()->attach(
            $team = Team::create($data)
        );

        $user->currentTeam()->associate($team);
        $user->save();

        setPermissionsTeamId($team->id);
        $user->assignRole('team admin');
    }
}
