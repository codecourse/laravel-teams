<?php

use App\Models\User;

use function Pest\Laravel\actingAs;

it('aborts the request if the user does not belong to the team', function () {
    $anotherUser = User::factory()->create();

    $user = User::factory()->create();

    $user->currentTeam()->associate($anotherUser->currentTeam)->save();

    actingAs($user)
        ->get(route('dashboard'))
        ->assertForbidden();
});
