<?php

namespace App;

use App\Team;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use Notifiable, LaratrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * [isOnlyAdminInTeam description]
     * @param  Team    $team [description]
     * @return boolean       [description]
     */
    public function isOnlyAdminInTeam(Team $team)
    {
        return  $this->hasRole('team_admin', $team->id) &&
                $team->users()->whereRoleIs('team_admin', $team->id)->count() === 1;
    }

    /**
     * [teams description]
     * @return [type] [description]
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class)
            ->withTimestamps();
    }
}
