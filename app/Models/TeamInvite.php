<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamInvite extends Model
{
    /** @use HasFactory<\Database\Factories\TeamInviteFactory> */
    use HasFactory;

    protected $guarded = false;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
