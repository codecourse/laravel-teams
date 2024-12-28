<?php

use App\Http\Middleware\TeamsPermission;
use App\Mail\TeamInvitation;
use App\Models\Team;
use App\Models\TeamInvite;
use App\Models\User;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

afterEach(function () {
    Str::createRandomStringsNormally();
});

it('creates an invite', function () {
    Mail::fake();

    $user = User::factory()->create();

    Str::createRandomStringsUsing(fn () => 'abc');

    actingAs($user)
        ->post(route('team.invites.store', $user->currentTeam), [
            'email' => $email = 'mabel@codecourse.com',
        ])
        ->assertRedirect();

    Mail::assertSent(TeamInvitation::class, function (TeamInvitation $mail) use ($email) {
        return $mail->hasTo($email) &&
                $mail->teamInvite->token === 'abc';
    });

    assertDatabaseHas('team_invites', [
        'team_id' => $user->currentTeam->id,
        'email' => $email,
        'token' => 'abc',
    ]);
});

it('requires an email address', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('team.invites.store', $user->currentTeam))
        ->assertSessionHasErrors('email');
});

it('requires an valid email address', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('team.invites.store', $user->currentTeam), [
            'email' => 'abc',
        ])
        ->assertSessionHasErrors('email');
});

it('fails to create invite if email already used', function () {
    $user = User::factory()->create();

    TeamInvite::factory()->create([
        'team_id' => $user->currentTeam->id,
        'email' => $email = 'mabel@codecourse.com',
    ]);

    actingAs($user)
        ->post(route('team.invites.store', $user->currentTeam), [
            'email' => $email,
        ])
        ->assertInvalid();
});

it('creates invite if email already invited to another team', function () {
    $user = User::factory()->create();

    TeamInvite::factory()
        ->for(Team::factory())
        ->create([
            'email' => $email = 'mabel@codecourse.com',
        ]);

    actingAs($user)
        ->post(route('team.invites.store', $user->currentTeam), [
            'email' => $email,
        ])
        ->assertValid();
});

it('fails to send invite without permission', function () {
    $user = User::factory()->create();

    $user->teams()->attach(
        $anotherTeam = Team::factory()->create()
    );

    setPermissionsTeamId($anotherTeam->id);

    actingAs($user)
        ->withoutMiddleware(TeamsPermission::class)
        ->post(route('team.invites.store', $anotherTeam), [
            'email' => 'mabel@codecourse.com',
        ])
        ->assertForbidden();
});

it('can revoke an invite', function () {
    $user = User::factory()->create();

    $invite = TeamInvite::factory()->create([
        'team_id' => $user->currentTeam->id,
    ]);

    actingAs($user)
        ->delete(route('team.invites.destroy', [$user->currentTeam, $invite]))
        ->assertRedirect('/team');

    assertDatabaseMissing('team_invites', [
        'team_id' => $user->currentTeam->id,
        'token' => $invite->token,
        'email' => $invite->email,
    ]);
});

it('can not revoke an invite without permission', function () {
    $user = User::factory()->create();

    $user->teams()->attach(
        $anotherTeam = Team::factory()->create()
    );

    $invite = TeamInvite::factory()->create([
        'team_id' => $anotherTeam->id,
    ]);

    setPermissionsTeamId($anotherTeam);

    actingAs($user)
        ->withoutMiddleware(TeamsPermission::class)
        ->delete(route('team.invites.destroy', [$user->currentTeam, $invite]))
        ->assertForbidden();
});

it('fails to accept invite if route is not signed', function () {
    $invite = TeamInvite::factory()
        ->for(Team::factory()->create())
        ->create();

    $acceptingUser = User::factory()->create();

    actingAs($acceptingUser)
        ->get('/team/invites/accept?token='.$invite->token)
        ->assertForbidden();
});

it('can accept an invite', function () {
    $invite = TeamInvite::factory()
        ->for(Team::factory()->create())
        ->create();

    $acceptingUser = User::factory()->create();

    actingAs($acceptingUser)
        ->withoutMiddleware(ValidateSignature::class)
        ->get('/team/invites/accept?token='.$invite->token)
        ->assertRedirect('/dashboard');

    expect($acceptingUser->teams->contains($invite->team))->toBeTrue()
        ->and($acceptingUser->hasRole('team member'))->toBeTrue()
        ->and($acceptingUser->currentTeam->id)->toBe($invite->team_id);

    assertDatabaseMissing('team_invites', [
        'team_id' => $invite->team_id,
        'token' => $invite->token,
        'email' => $invite->email,
    ]);
});
