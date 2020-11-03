<?php

namespace App\Services;

use App\Services\LearnerRecordStoreServiceInterface;
use Illuminate\Http\Request;
use App\Exceptions\GeneralException;
use \TinCan\RemoteLRS;
use \TinCan\Statement;
use \TinCan\Agent;
use \TinCan\Verb;
use \TinCan\Activity;
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

    /**
     * Query Statements
     *
     * Get statements from the LRS based on parameters.
     * 
     * @param array $queryParams An array of query parameters
     * @return \LRSResponse
     */
    public function queryStatements($queryParams)
    {
        return $this->service->queryStatements($queryParams);
    }

    public function makeAgentFromJSON($json)
    {
        $actor = \TinCan\Agent::fromJSON($json);
        return $actor;
    }

    public function makeVerb($iri)
    {
        $verb = new \TinCan\Verb(
            ['id' => $iri]
        );
        return $verb;
    }

    public function makeActivity($iri)
    {
        $activity = new \TinCan\Activity(
            ['id' => $iri]
        );
        return $activity;
    }

    public function getCompletedStatements($data)
    {
        $actor = $this->makeAgentFromJSON($data['actor']);
        $verb = $this->makeVerb('http://adlnet.gov/expapi/verbs/completed');
        $activity = $this->makeActivity($data['activity']);
        
        $params = [];
        $params['agent'] = $actor;
        $params['verb'] = $verb;
        $params['activity'] = $activity;
        $params['related_activities'] = true;
        $response = $this->queryStatements($params);
        if ($response->success) {
            return $response->content->getStatements();
        }
        else {
            throw new GeneralException($response->content);
        }
    }

    public function getAnswersStatements($data)
    {
        $actor = $this->makeAgentFromJSON($data['actor']);
        $verb = $this->makeVerb('http://adlnet.gov/expapi/verbs/answered');
        $activity = $this->makeActivity($data['activity']);
        $params = [];
        $params['agent'] = $actor;
        $params['verb'] = $verb;
        $params['activity'] = $activity;
        // $params['limit'] = 2;
        $params['ascending'] = true;
        $params['related_activities'] = true;
        $response = $this->queryStatements($params);
        
        if ($response->success) {
            return $response->content->getStatements();
        }
        else {
            throw new GeneralException($response->content);
        }
    }

    public function getAnswersStatementsWithResults($data) {
        $allAnswers = $this->getAnswersStatements($data);
        $filtered = [];
        if ($allAnswers) {
            // iterate and find the statements that have results & Category.
            foreach($allAnswers as $statement) {
                $result = $statement->getResult();
                // Get Category context
                $contextActivities = $statement->getContext()->getContextActivities();
                $category = $contextActivities->getCategory();
                if (!empty($category) && !empty($result)) {
                    $filtered[] = $statement;
                }
            }
        }

        return $filtered;
    }

}
