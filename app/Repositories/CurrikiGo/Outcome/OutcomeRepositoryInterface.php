<?php

namespace App\Repositories\CurrikiGo\Outcome;

interface OutcomeRepositoryInterface
{
    /**
     * Get the outcome summary for a student
     * @param User $user
     */
    public function getStudentOutcome($actor, $activity);
}
