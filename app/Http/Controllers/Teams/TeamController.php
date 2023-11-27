<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Team;
use App\Teams\Roles;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * [__construct description]
     * @param Request $request [description]
     */
    public function __construct(Request $request)
    {
        $this->middleware('in_team:' . $request->team)
            ->except(['index', 'store']);

        $this->middleware(['permission:delete team,' . $request->team])
            ->only(['delete', 'destroy']);
    }

    /**
     * [index description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        $teams = $request->user()->teams;

        return view('teams.index', compact('teams'));
    }

    /**
     * [show description]
     * @return [type] [description]
     */
    public function show(Team $team)
    {
        return view('teams.show', compact('team'));
    }

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $user = $request->user();

        $team = $user->teams()->create($request->only('name'));

        $user->attachRole(Roles::$roleWhenCreatingTeam, $team->id);

        return redirect()->route('teams.index');
    }

    /**
     * [delete description]
     * @param  Team   $team [description]
     * @return [type]       [description]
     */
    public function delete(Team $team)
    {
        return view('teams.delete', compact('team'));
    }

    /**
     * [destroy description]
     * @param  Team   $team [description]
     * @return [type]       [description]
     */
    public function destroy(Team $team)
    {
        $team->delete();

        return redirect()->route('teams.index');
    }
}
