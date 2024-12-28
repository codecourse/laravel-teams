<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamMemberDestroyRequest;
use App\Http\Requests\TeamMemberUpdateRequest;
use App\Models\Team;
use App\Models\User;

class TeamMemberController extends Controller
{
    public function destroy(TeamMemberDestroyRequest $request, Team $team, User $user)
    {
        $team->members()->detach($user);

        $user->currentTeam()->associate($user->teams()->first())->save();
        $user->roles()->detach();

        return redirect()->route('team.edit');
    }

    public function update(TeamMemberUpdateRequest $request, Team $team, User $user)
    {
        if ($request->has('role')) {
            tap($team->members->find($user), function (User $member) use ($request) {
                $member->roles()->detach();
                $member->assignRole($request->role);
            });
        }

        return back();
    }
}
