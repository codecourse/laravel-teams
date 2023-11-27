<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    /**
     * [scopeTeams description]
     * @param  Builder $builder [description]
     * @return [type]           [description]
     */
    public function scopeTeams(Builder $builder)
    {
        return $builder->where('teams', true);
    }
}
