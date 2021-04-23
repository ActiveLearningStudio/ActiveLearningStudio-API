<?php

namespace App\Repositories\CurrikiGo\Outcome;

interface OutcomeRepositoryInterface
{
    /**
     * Get the outcome summary for a student
     * @param string $actor
     * @param string $activity
     * @return array
     */
    public function getStudentOutcome($actor, $activity);
}
