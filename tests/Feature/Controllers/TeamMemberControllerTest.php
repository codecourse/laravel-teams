<?php

use App\Http\Middleware\TeamsPermission;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('can remove a member from the team', function () {
    $user = User::factory()->create();

    $user->currentTeam->members()->attach(
        $member = User::factory()->create()
    );

    setPermissionsTeamId($user->currentTeam->id);

    $member->currentTeam()->associate($user->currentTeam)->save();
    $member->assignRole('team admin');

    actingAs($user)
        ->delete(route('team.members.destroy', [$user->currentTeam, $member]))
        ->assertRedirect();

    expect($user->fresh()->currentTeam->members->contains($member))->toBeFalse()
        ->and($member->fresh()->current_team_id)->not->toEqual($user->currentTeam->id)
        ->and($member->fresh()->roles)->toHaveCount(0);
});

it('can not remove a member from the team without permission', function () {
    $user = User::factory()->create();

    $anotherUser = User::factory()->create();

    $user->currentTeam->members()->attach(
        $member = User::factory()->create()
    );

    setPermissionsTeamId($user->currentTeam->id);

    actingAs($anotherUser)
        ->withoutMiddleware(TeamsPermission::class)
        ->delete(route('team.members.destroy', [$user->currentTeam, $member]))
        ->assertForbidden();
});

it('can not remove self from team', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->delete(route('team.members.destroy', [$user->currentTeam, $user]))
        ->assertForbidden();
});

it('updates a role', function () {
    $user = User::factory()->create();

    $user->currentTeam->members()->attach(
        $member = User::factory()->createQuietly()
    );

    setPermissionsTeamId($user->currentTeam->id);
    $member->assignRole('team member');

    actingAs($user)
        ->patch(route('team.members.update', [$user->currentTeam, $member]), [
            'role' => 'team admin',
        ])
        ->assertRedirect();

    expect($member->hasRole('team admin'))->toBeTrue()
        ->and($member->roles->count())->toBe(1);
});

it('only updates role if provided', function () {
    $user = User::factory()->create();

    $user->currentTeam->members()->attach(
        $member = User::factory()->createQuietly()
    );

    setPermissionsTeamId($user->currentTeam->id);
    $member->assignRole('team member');

    actingAs($user)
        ->patch(route('team.members.update', [$user->currentTeam, $member]), [
            //
        ])
        ->assertRedirect();

    expect($member->hasRole('team member'))->toBeTrue()
        ->and($member->roles->count())->toBe(1);
});

it('does not update the role if no permission', function () {
    $user = User::factory()->create();

    $user->currentTeam->members()->attach(
        $anotherUser = User::factory()->create()
    );

    setPermissionsTeamId($user->currentTeam->id);
    $anotherUser->assignRole('team member');

    actingAs($anotherUser)
        ->withoutMiddleware(TeamsPermission::class)
        ->patch(route('team.members.update', [$user->currentTeam, $user]), [
            'role' => 'team member',
        ])
        ->assertForbidden();
});

it('does not update the user if they are not in the team', function () {
    $user = User::factory()->create();
    $anotherUser = User::factory()->create();

    actingAs($user)
        ->patch(route('team.members.update', [$user->currentTeam, $anotherUser]), [
            'role' => 'team member',
        ])
        ->assertForbidden();
});

it('validates the role to make sure it exists', function () {
    $user = User::factory()->create();

    $user->currentTeam->members()->attach(
        $member = User::factory()->create()
    );

    setPermissionsTeamId($user->currentTeam->id);
    $member->assignRole('team member');

    actingAs($user)
        ->patch(route('team.members.update', [$user->currentTeam, $member]), [
            'role' => 'some wrong role',
        ])
        ->assertInvalid()
        ->assertSessionHasErrors(['role']);
});
