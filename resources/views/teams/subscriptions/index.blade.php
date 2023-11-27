@extends('layouts.team')

@section('teamcontent')
    <div class="row justify-content-center">
        <div class="col-md-3">
            @include('teams.partials._nav')
        </div>
        <div class="col-md-9">
            @if(!$team->hasSubscription())
                <div class="card mb-4">
                    <div class="card-header">Subscription</div>

                    <div class="card-body">
                        <form action="{{ route('teams.subscriptions.store', $team) }}" method="post">
                            @csrf

                            <div class="form-group">
                                <label>Choose a plan</label>

                                @foreach($plans as $index => $plan)
                                    <div class="form-check">
                                        <input
                                            type="radio"
                                            name="plan"
                                            id="plan_{{ $plan->id }}"
                                            class="form-check-input"
                                            value="{{ $plan->id }}"
                                            {{ $index === 0 ? 'checked' : '' }}
                                        >

                                        <label class="form-check-label" for="plan_{{ $plan->id }}">
                                            {{ $plan->name }} ({{ $plan->teams_limit }} users)
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-group">
                                <label>Payment details</label>

                                <stripe />
                            </div>

                            <button type="submit" class="btn btn-primary">Process payment</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="card mb-4">
                    <div class="card-header">Team subscription</div>

                    <div class="card-body">
                        You're on the <strong>{{ $team->plan->name }}</strong> plan (<strong>{{ $team->plan->teams_limit }}</strong> users)
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">Swap subscription</div>

                    <div class="card-body">
                        @include('teams.subscriptions.partials._usage')

                        <form action="{{ route('teams.subscriptions.swap.store', $team) }}" method="post">
                            @csrf

                            <div class="form-group">
                                <label>Choose a plan</label>

                                @foreach($plans as $index => $plan)
                                    <div class="form-check">
                                        <input
                                            type="radio"
                                            name="plan"
                                            id="plan_{{ $plan->id }}"
                                            class="form-check-input"
                                            value="{{ $plan->id }}"
                                            {{ !$team->canDowngrade($plan) ? 'disabled' : '' }}
                                            {{ $team->isOnPlan($plan->provider_id) ? 'checked' : '' }}
                                        >

                                        <label class="form-check-label" for="plan_{{ $plan->id }}">
                                            {{ $plan->name }} ({{ $plan->teams_limit }} users)
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <button type="submit" class="btn btn-primary">Swap</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
