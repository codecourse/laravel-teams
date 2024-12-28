<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[ObservedBy(UserObserver::class)]
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = false;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profilePhotoUrl()
    {
        return 'https://gravatar.com/avatar/'.md5($this->email).'?s=100';
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function currentTeam()
    {
        return $this->belongsTo(Team::class, 'current_team_id');
    }

    public function projects()
    {
        return $this->hasManyThrough(
            Project::class,
            TeamUser::class,
            'user_id',
            'team_id',
            'id',
            'team_id'
        );
    }

    public function belongsToTeam($team): bool
    {
        return $this->teams()->where('team_id', $team->id)->exists();
    }

    public function hasTeamPermission($team, string $permission): bool
    {
        if ($this->hasRole('admin')) {
            return true;
        }

        $teamUser = $this->teams()
            ->where('team_id', $team->id)
            ->first();

        return $teamUser && $teamUser->pivot->role === 'admin';
    }
}
