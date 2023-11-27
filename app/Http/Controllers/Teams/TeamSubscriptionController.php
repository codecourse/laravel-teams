<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Plan;
use App\Team;
use Illuminate\Http\Request;

class TeamSubscriptionController extends Controller
{
    /**
     * [index description]
     * @param  Team   $team [description]
     * @return [type]       [description]
     */
    public function index(Team $team)
    {
        $plans = Plan::teams()->get();

        return view('teams.subscriptions.index', compact('team', 'plans'));
    }

    /**
     * [store description]
     * @param  Request $request [description]
     * @param  Team    $team    [description]
     * @return [type]           [description]
     */
    public function store(Request $request, Team $team)
    {
        $this->validate($request, [
            'token' => 'required',
            'plan' => 'required|exists:plans,id'
        ]);

        $plan = Plan::find($request->plan);

        $team->newSubscription('main', $plan->provider_id)
            ->create($request->token);

        return back();
    }
}
