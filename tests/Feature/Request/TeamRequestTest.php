<?php

use App\Models\Team;
use App\Models\User;

it('can access the current team through the request', function () {
    $user = User::factory()->create();

    request()->setUserResolver(function () use ($user) {
        return $user;
    });

    expect(request()->team())->toBeInstanceOf(Team::class)
        ->id->toBe($user->current_team_id);
});

it('returns null if no user', function () {
    expect(request()->team())->toBeNull();
});

it('can access the current team through the team helper function', function () {
    $user = User::factory()->create();

    request()->setUserResolver(function () use ($user) {
        return $user;
    });

    expect(team())->toBeInstanceOf(Team::class)
        ->id->toBe($user->current_team_id);
});
