<?php

namespace App\Subscriptions\Traits;

trait HasSubscriptions
{
    /**
     * [hasSubscription description]
     * @return boolean [description]
     */
    public function hasSubscription()
    {
        return $this->subscribed('main');
    }

    /**
     * [isOnPlan description]
     * @param  [type]  $plan [description]
     * @return boolean       [description]
     */
    public function isOnPlan($plan)
    {
        return $this->subscribedToPlan($plan, 'main');
    }

    /**
     * [currentSubscription description]
     * @return [type] [description]
     */
    public function currentSubscription()
    {
        return $this->subscription('main');
    }
}
