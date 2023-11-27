<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Plan;
use App\Team;
use Illuminate\Http\Request;

class TeamSubscriptionSwapController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('in_team:' . $request->team);

        $this->middleware(['permission:manage team subscription,' . $request->team]);
    }

    public function store(Request $request, Team $team)
    {
        $this->validate($request, [
            'plan' => 'required|exists:plans,id'
        ]);

        $plan = Plan::find($request->plan);

        if (!$team->canDowngrade($plan)) {
            return back();
        }

        $team->currentSubscription()->swap(
            $plan->provider_id
        );

        return back();
    }
}
