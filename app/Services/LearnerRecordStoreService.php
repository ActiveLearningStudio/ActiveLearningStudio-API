<?php

namespace App\Services;

use App\Services\LearnerRecordStoreServiceInterface;
use Illuminate\Http\Request;
use \TinCan\RemoteLRS;
use \TinCan\Statement;
use \TinCan\LRSResponse;

/**
 * Learner Record Store Service class
 */
class LearnerRecordStoreService implements LearnerRecordStoreServiceInterface
{
    /**
     * LRS Serivce object
     *
     * @var \RemoteLRS
     */
    protected $service;

    /**
     * Creates an instance of the class
     *
     * @return void
     */
    function __construct()
    {
        $this->service = new \TinCan\RemoteLRS(
            config('xapi.lrs_remote_url'),
            config('xapi.xapi_version'),
            config('xapi.lrs_username'),
            config('xapi.lrs_password')
        );
    }

    /**
     * Save Statement
     *
     * @param string $statement A stringified xAPI statment
     * @return \LRSResponse
     */
    public function saveStatement($statement)
    {
        $statement = \TinCan\Statement::fromJSON($statement);
        return $this->service->saveStatement($statement);
    }

}
