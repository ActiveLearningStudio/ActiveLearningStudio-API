<?php

namespace App\Observers;

use App\Jobs\CalculateActivityStorage;
use App\Models\Activity;
use App\Models\ActivityViewLog;

class ActivityObserver
{
    /**
     * Handle the activity "created" event.
     *
     * @param  \App\Activity  $activity
     * @return void
     */
    public function created(Activity $activity)
    {
        $this->schedule_jobs($activity, 'created');
    }

    /**
     * Handle the activity "updated" event.
     *
     * @param  \App\Activity  $activity
     * @return void
     */
    public function updated(Activity $activity)
    {
        $this->schedule_jobs($activity, 'updated');
    }

    private function schedule_jobs($activity, $message){
        // Schedule storage accounting job
        CalculateActivityStorage::dispatch($activity);
    }

}
