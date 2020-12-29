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
use \TinCan\Extensions;
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
        $statement = Statement::fromJSON($statement);
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

    /**
     * Make Agent from JSON
     *
     * 
     * @param string $json A JSON string of an actor
     * @return \Agent
     */
    public function makeAgentFromJSON($json)
    {
        $actor = Agent::fromJSON($json);
        return $actor;
    }

    /**
     * Make Verb from an IRI
     *
     * 
     * @param string $iri An IRI string for a verb
     * @return \Verb
     */
    public function makeVerb($iri)
    {
        $verb = new Verb(
            ['id' => $iri]
        );
        return $verb;
    }

    /**
     * Make Activity from an IRI
     *
     * 
     * @param string $iri An IRI string for an activity object
     * @return \Activity
     */
    public function makeActivity($iri)
    {
        $activity = new Activity(
            ['id' => $iri]
        );
        return $activity;
    }

    /**
     * Get 'completed' statements from LRS based on filters
     *
     * @param array $data An array of filters.
     * @param int $limit The number of statements to fetch
     * @throws GeneralException
     * @return array
     */
    public function getCompletedStatements(array $data, int $limit = 0)
    {
        if (empty($data) || !array_key_exists('actor', $data) || !array_key_exists('activity', $data)) {
            throw new GeneralException("XAPI statement's actor and activity properties are needed.");
        }
        $actor = $this->makeAgentFromJSON($data['actor']);
        $verb = $this->makeVerb(self::COMPLETED_VERB_ID);
        $activity = $this->makeActivity($data['activity']);
        
        $params = [];
        $params['agent'] = $actor;
        $params['verb'] = $verb;
        $params['activity'] = $activity;
        $params['related_activities'] = true;
        if ($limit > 0) {
            $params['limit'] = $limit;
        }
        $response = $this->queryStatements($params);
        if ($response->success) {
            return $response->content->getStatements();
        }
        throw new GeneralException($response->content);
    }

    /**
     * Get 'answered' statements from LRS based on filters
     *
     * @param array $data An array of filters.
     * @throws GeneralException
     * @return array
     */
    public function getAnsweredStatements(array $data)
    {
        if (empty($data) || !array_key_exists('actor', $data) || !array_key_exists('activity', $data)) {
            throw new GeneralException("XAPI statement's actor and activity properties are needed.");
        }
        $actor = $this->makeAgentFromJSON($data['actor']);
        $verb = $this->makeVerb(self::ANSWERED_VERB_ID);
        $activity = $this->makeActivity($data['activity']);
        $params = [];
        $params['agent'] = $actor;
        $params['verb'] = $verb;
        $params['activity'] = $activity;
        $params['ascending'] = false;
        $params['related_activities'] = true;
        $response = $this->queryStatements($params);
        
        if ($response->success) {
            return $response->content->getStatements();
        }
        throw new GeneralException($response->content);
    }

    /**
     * Get statements by verb from LRS based on filters
     *
     * @param string $verb The name of the verb to get statements for
     * @param array $data An array of filters.
     * @throws GeneralException
     * @return array
     */
    public function getStatementsByVerb($verb, array $data)
    {
        $verbId = $this->getVerbFromName($verb);
        if (empty($data) || !$verbId || !array_key_exists('actor', $data) || !array_key_exists('activity', $data)) {
            throw new GeneralException("XAPI statement's actor, verb and activity properties are needed.");
        }
        $actor = $this->makeAgentFromJSON($data['actor']);
        $verb = $this->makeVerb($verbId);
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
        throw new GeneralException($response->content);
    }

    /**
     * Get the latest 'answered' statements from LRS based on filters
     * that have results and category in context in them.
     * 
     * @param array $data An array of filters.
     * @throws GeneralException
     * @return array
     */
    public function getLatestAnsweredStatementsWithResults(array $data)
    {
        $allAnswers = $this->getAnsweredStatements($data);
        $filtered = [];
        if ($allAnswers) {
            // iterate and find the statements that have results & Category.
            foreach ($allAnswers as $statement) {
                $result = $statement->getResult();
                // Get Category context
                $contextActivities = $statement->getContext()->getContextActivities();
                $category = $contextActivities->getCategory();
                if (!empty($category) && !empty($result)) {
                    // Each quiz within the activity is identified by a unique GUID.
                    // We only need to take the most recent submission on an activity into account.
                    // We've sorted statements in descending order, so the first entry for a subId is the latest
                    // We also need to exclude 'answered' statements for the aggregate types
                    // Rule for that is, if we're able to find more than 1 answered statements for the object id, for the same attempt
                    // then it's an aggregate.
                    $objectId = $statement->getTarget()->getId();
                    $isAggregateH5P = $this->isAggregateH5P($data, $objectId); 
                    // Get activity subID for this statement.
                    $h5pSubContentId = $this->getH5PSubContenIdFromStatement($statement);
                    if (!array_key_exists($h5pSubContentId, $filtered) && !$isAggregateH5P) {
                        $filtered[$h5pSubContentId] = $statement;
                    }
                }
            }
        }
        return $filtered;
    }

    /**
     * Get the 'skipped' statements from LRS based on filters
     * 
     * @param array $data An array of filters.
     * @throws GeneralException
     * @return array
     */
    public function getSkippedStatements(array $data)
    {
        $skipped = $this->getStatementsByVerb('skipped', $data);
        $filtered = [];
        if ($skipped) {
            // iterate and find the statements that have results.
            foreach ($skipped as $statement) {
                $result = $statement->getResult();
                // Get Category context
                $contextActivities = $statement->getContext()->getContextActivities();
                if (!empty($result)) {
                    // Get activity subID for this statement.
                    // Each quiz within the activity is identified by a unique GUID.
                    // We only need to take the most recent submission on an activity into account.
                    // We've sorted statements in descending order, so the first entry for a subId is the latest
                    $h5pSubContentId = $this->getH5PSubContenIdFromStatement($statement);
                    if (!array_key_exists($h5pSubContentId, $filtered)) {
                        $filtered[$h5pSubContentId] = $statement;
                    }
                }
            }
        }
        return $filtered;
    }

    /**
     * Get the 'attempted' statements from LRS based on filters
     * 
     * @param array $data An array of filters.
     * @throws GeneralException
     * @return array
     */
    public function getAttemptedStatements(array $data)
    {
        $attempted = $this->getStatementsByVerb('attempted', $data);
        $filtered = [];
        if ($attempted) {
            // iterate and find the statements that have results.
            foreach ($attempted as $statement) {
                // Get Parent context
                $contextActivities = $statement->getContext()->getContextActivities();
                $parent = $contextActivities->getParent();
                
                if (!empty($parent)) {
                    $objectId = $statement->getTarget()->getId();
                    $isAggregateH5P = $this->isAggregateH5P($data, $objectId); 
                    // Get activity subID for this statement.
                    $h5pSubContentId = $this->getH5PSubContenIdFromStatement($statement);
                    if (!array_key_exists($h5pSubContentId, $filtered) && !$isAggregateH5P) {
                        $filtered[$h5pSubContentId] = $statement;
                    }
                }
            }
        }
        return $filtered;
    }

    /**
     * Find if the statement is for an aggregate H5P
     * 
     * @param array $data An array of filters.
     * @param string $objectId An activity Object Id
     * @throws GeneralException
     * @return bool
     */
    public function isAggregateH5P(array $data, string $objectId)
    {
        $attemptId = $data['activity'];
        // Get all related answered statetmentn for the object id
        $data['activity'] = $objectId;
        $allAnswers = $this->getAnsweredStatements($data);
        $count = 0;
        if ($allAnswers) {
            // iterate and find the statements that have results & Category.
            foreach ($allAnswers as $statement) {
                $attemptIRI = '';
                $result = $statement->getResult();
                // Get Category context
                $contextActivities = $statement->getContext()->getContextActivities();
                $category = $contextActivities->getCategory();
                
                $other = $contextActivities->getOther();
                if (!empty($other)) {
                    $attemptIRI = end($other)->getId();
                }
                if ($attemptIRI === $attemptId && (!empty($category) && !empty($result))) {
                    ++$count;
                }
                // If we have 2 or more records, then it's an aggregate H5P statement
                if ($count > 1) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Get summary of an 'answered' statement
     * 
     * @param Statement $statement A TinCan API statement object.
     * @return array
     */
    public function getStatementSummary(Statement $statement)
    {
        $summary = [];
        $target = $statement->getTarget();
        $nameOfActivity = 'Unknown Quiz set';
        $definition = $target->getDefinition();
        // In some cases, we do not have a 'name' property for the object.
        // So, we've added an additional check here.
        // @todo - the LRS statements generated need to have this property
        if (!$definition->getName()->isEmpty()) {
            $nameOfActivity = $definition->getName()->getNegotiatedLanguageString();
        }
        $result = $statement->getResult();
        $summary['name'] = $nameOfActivity;
        if ($result) {
            $summary['score'] = [
                'raw' => $result->getScore()->getRaw(),
                'max' => $result->getScore()->getMax(),
            ];
            $summary['duration'] = $this->formatDuration($result->getDuration());
        } else {
            $summary['score'] = [
                'raw' => 0,
                'max' => 0
            ];
            $summary['duration'] = '00:00';
        }
        
        // Get activity duration
        $extensions = $statement->getContext()->getExtensions();
        $endingPoint = $this->getEndingPointExtension($extensions);
        
        // this is basically the ending point (or the seek point) on the video where the quiz is set.
        $summary['ending-point'] = ($endingPoint ? $endingPoint : '');
        // Get Verb
        $summary['verb'] = $statement->getVerb()->getDisplay()->getNegotiatedLanguageString();
        
        return $summary;
    }

    /**
     * Retrieve 'ending-point' from a list of extensions in a statement.
     * 
     * @param Extensions $extensions A List of Extensions in a statement.
     * @return string
     */
    public function getEndingPointExtension(Extensions $extensions)
    {
        $extensionsList = $extensions->asVersion();
        $endPoint = '';
        $keyName = self::EXTENSION_ENDING_POINT_IRI;
        // find the end point
        if (!empty($extensionsList) && array_key_exists($keyName, $extensionsList)) {
            $endPoint = $this->formatDuration($extensionsList[$keyName]);
        }
        return $endPoint;
    }

    /**
     * Format 'duration' value in seconds to hh:mm:ss format
     * e.g., PT24S to 0:24
     * 
     * @param string $duration
     * @return string
     */
    public function formatDuration($duration)
    {
        $raw_duration = str_replace(array('PT', 'S'), '', $duration);
        $seconds = round($raw_duration);
     
        $formatted = sprintf('%02d:%02d', ($seconds / 60 % 60), $seconds % 60);
        if (($seconds / 3600) >= 1) {
            $formatted = sprintf('%02d:%02d:%02d', ($seconds / 3600), ($seconds / 60 % 60), $seconds % 60);
        }

        return $formatted;
    }

    /**
     * Retrieve H5P SubContent Id from a list of extensions in a statement.
     * 
     * @param Statement $statement An XAPI Statement.
     * @return string
     */
    public function getH5PSubContenIdFromStatement(Statement $statement)
    {
        $target = $statement->getTarget();
        $extensionsList = $target->getDefinition()->getExtensions()->asVersion();
        $keyName = self::EXTENSION_H5P_SUBCONTENT_ID;
        // find the sub content id
        return (!empty($extensionsList) && array_key_exists($keyName, $extensionsList) ? $extensionsList[$keyName] : '');
    }

    /**
     * Get Verb ID from name.
     * 
     * @param string $verb Name of the verb. Example: answered
     * @return string|bool
     */
    public function getVerbFromName($verb)
    {
        $verbsList = [
            'answered' => self::ANSWERED_VERB_ID,
            'completed' => self::COMPLETED_VERB_ID,
            'skipped' => self::SKIPPED_VERB_ID,
            'attempted' => self::ATTEMPTED_VERB_ID
        ];
        return (array_key_exists($verb, $verbsList) ? $verbsList[$verb] : false);
    }
    
}
