<?php

namespace App\Http\Controllers;

use App\Http\Requests\SetCurrentTeamRequest;
use App\Http\Requests\TeamLeaveRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Models\Team;
use Illuminate\Http\Request;

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
            'team' => $request->user()->currentTeam,
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
}
