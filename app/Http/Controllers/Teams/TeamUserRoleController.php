<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Team;
use App\Teams\Roles;
use App\User;
use Illuminate\Http\Request;

class TeamUserRoleController extends Controller
{
    /**
     * [__construct description]
     * @param Request $request [description]
     */
    public function __construct(Request $request)
    {
        $this->middleware('in_team:' . $request->team);

        $this->middleware(['permission:change user roles,' . $request->team]);
    }

    /**
     * [edit description]
     * @param  Team   $team [description]
     * @param  User   $user [description]
     * @return [type]       [description]
     */
    public function edit(Team $team, User $user)
    {
        $roles = Roles::$roles;

        return view('teams.users.roles.edit', compact('team', 'user', 'roles'));
    }

    /**
     * [update description]
     * @param  Team   $team [description]
     * @param  User   $user [description]
     * @return [type]       [description]
     */
    public function update(Request $request, Team $team, User $user)
    {
        $this->validate($request, [
            'role' => 'required|exists:roles,name'
        ]);

        if (!$team->users->contains($user)) {
            return back();
        }

        if ($user->isOnlyAdminInTeam($team)) {
            return back();
        }

        $user->syncRoles([$request->role], $team->id);

        return redirect()->route('teams.users.index', $team);
    }
}
