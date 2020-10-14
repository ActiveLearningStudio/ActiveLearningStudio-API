<?php

namespace App\Services;

/**
 * Interface for Learner Record Store service
 */
interface LearnerRecordStoreServiceInterface {

    /**
     * Save Statement
     *
     * @param string $statement A stringified xAPI statment
     * @return \LRSResponse
     */
    public function saveStatement($statement);

}
