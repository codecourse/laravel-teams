<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\User;
use App\Models\TeamInvite;

class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;

    protected $guarded = false;

    public function members()
    {
        return $this->belongsToMany(User::class);
    }

    public function invites()
    {
        return $this->hasMany(TeamInvite::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

}
