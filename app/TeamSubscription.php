<?php

namespace App;

use App\Team;
use Laravel\Cashier\Subscription;

class TeamSubscription extends Subscription
{
    /**
     * [owner description]
     * @return [type] [description]
     */
    public function owner()
    {
        return $this->belongsTo(Team::class, (new Team)->getForeignKey());
    }
}
