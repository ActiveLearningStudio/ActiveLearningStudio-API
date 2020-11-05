<?php

namespace App\Services;
use \TinCan\Statement;
use \TinCan\Agent;
use \TinCan\Verb;
use \TinCan\Activity;
use \TinCan\Extensions;

/**
 * Interface for Learner Record Store service
 */
interface LearnerRecordStoreServiceInterface
{

    /**
     * Ending-point extension IRI
     * 
     * @var string
     */
    const EXTENSION_ENDING_POINT_IRI = 'http://id.tincanapi.com/extension/ending-point';

    /**
     * H5P xAPI subContent ID 
     * 
     * @var string
     */
    const EXTENSION_H5P_SUBCONTENT_ID = 'http://h5p.org/x-api/h5p-subContentId';
    
    /**
     * Answered verb id for XAPI statements
     * 
     * @var string
     */
    const ANSWERED_VERB_ID = 'http://adlnet.gov/expapi/verbs/answered';

    /**
     * Completed verb id for XAPI statements
     * 
     * @var string
     */
    const COMPLETED_VERB_ID = 'http://adlnet.gov/expapi/verbs/completed';
    
    /**
     * Save Statement
     *
     * @param string $statement A stringified xAPI statment
     * @return \LRSResponse
     */
    public function saveStatement($statement);

    /**
     * Query Statements
     *
     * Get statements from the LRS based on parameters.
     * 
     * @param array $queryParams An array of query parameters
     * @return \LRSResponse
     */
    public function queryStatements($queryParams);

    /**
     * Make Agent from JSON
     *
     * 
     * @param string $json A JSON string of an actor
     * @return \Agent
     */
    public function makeAgentFromJSON($json);

    /**
     * Make Verb from an IRI
     *
     * 
     * @param string $iri An IRI string for a verb
     * @return \Verb
     */
    public function makeVerb($iri);

    /**
     * Make Activity from an IRI
     *
     * 
     * @param string $iri An IRI string for an activity object
     * @return \Activity
     */
    public function makeActivity($iri);

    /**
     * Get 'completed' statements from LRS based on filters
     *
     * @param array $data An array of filters.
     * @throws GeneralException
     * @return array
     */
    public function getCompletedStatements(array $data);

    /**
     * Get 'answered' statements from LRS based on filters
     *
     * @param array $data An array of filters.
     * @throws GeneralException
     * @return array
     */
    public function getAnsweredStatements(array $data);

    /**
     * Get the latest 'answered' statements from LRS based on filters
     * that have results and category in context in them.
     * 
     * @param array $data An array of filters.
     * @throws GeneralException
     * @return array
     */
    public function getLatestAnsweredStatementsWithResults(array $data);

    /**
     * Get summary of an 'answered' statement
     * 
     * @param Statement $statement A TinCan API statement object.
     * @return array
     */
    public function getStatementSummary(Statement $statement);

    /**
     * Retrieve 'ending-point' from a list of extensions in a statement.
     * 
     * @param Extensions $extensions A List of Extensions in a statement.
     * @return string
     */
    public function getEndingPointExtension(Extensions $extensions);

    /**
     * Format 'duration' value in seconds
     * e.g., PT24S to 0:24
     * 
     * @param string $duration
     * @return string
     */
    public function formatDuration($duration);

    /**
     * Retrieve H5P SubContent Id from a list of extensions in a statement.
     * 
     * @param Statement $statement An XAPI Statement.
     * @return string
     */
    public function getH5PSubContenIdFromStatement(Statement $statement);

}
