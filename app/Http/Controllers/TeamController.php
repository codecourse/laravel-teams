<?php

namespace App\Http\Controllers;

use App\Actions\CreateTeam;
use App\Http\Requests\SetCurrentTeamRequest;
use App\Http\Requests\TeamLeaveRequest;
use App\Http\Requests\TeamStoreRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Models\Team;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class TeamController extends Controller
{
    public function setCurrent(SetCurrentTeamRequest $request, Team $team)
    {
        $request->user()->currentTeam()->associate($team)->save();

        return back();
    }

    public function edit(Request $request)
    {
        return view('team.edit', [
            'team' => $request->user()->currentTeam->load(['members.roles', 'invites.team']),
            'roles' => Role::get()
        ]);
    }

    public function update(TeamUpdateRequest $request, Team $team)
    {
        $team->update($request->only('name'));

        return back()->withStatus('team-updated');
    }

    public function leave(TeamLeaveRequest $request, Team $team)
    {
        $user = $request->user();

        $user->teams()->detach($team);

        // Set current team to another team
        $user->currentTeam()->associate($user->fresh()->teams->first())->save();

        return redirect()->route('dashboard');
    }

    public function create()
    {
        return view('team.create');
    }

    public function store(TeamStoreRequest $request, CreateTeam $createTeam)
    {
        $createTeam->handle($request->user(), $request->validated());

        return back();
    }
}
