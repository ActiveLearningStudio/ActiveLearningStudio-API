<?php

namespace App\Services;

use App\Services\LearnerRecordStoreConstantsInterface;
use \TinCan\Statement;
use \TinCan\Agent;
use \TinCan\Verb;
use \TinCan\Activity;
use \TinCan\Extensions;
use \TinCan\LRSResponse;
use \TinCan\ActivityDefinition;

/**
 * Interface for Learner Record Store service
 */
interface LearnerRecordStoreServiceInterface extends LearnerRecordStoreConstantsInterface
{

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
     * @param int $limit The number of statements to fetch
     * @throws GeneralException
     * @return array
     */
    public function getCompletedStatements(array $data, int $limit = 0);

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

    /**
     * Get Verb ID from name.
     * 
     * @param string $verb Name of the verb. Example: answered
     * @return string|bool
     */
    public function getVerbFromName($verb);

    /**
     * Get statements by verb from LRS based on filters
     *
     * @param string $verb The name of the verb to get statements for
     * @param array $data An array of filters.
     * @param int $limit The number of statements to fetch
     * @throws GeneralException
     * @return array
     */
    public function getStatementsByVerb($verb, array $data, int $limit = 0);

    /**
     * Get the 'skipped' statements from LRS based on filters
     * 
     * @param array $data An array of filters.
     * @throws GeneralException
     * @return array
     */
    public function getSkippedStatements(array $data);

    /**
     * Get the 'attempted' statements from LRS based on filters
     * 
     * @param array $data An array of filters.
     * @throws GeneralException
     * @return array
     */
    public function getAttemptedStatements(array $data);

    /**
     * Find if the answered statement is for an aggregate H5P
     * 
     * @param array $data An array of filters.
     * @param string $objectId An activity Object Id
     * @throws GeneralException
     * @return bool
     */
    public function isAggregateH5P(array $data, string $objectId);

    /**
     * List of allowed non-scoring interactions.
     * 
     * @return array
     */
    public function allowedInteractionsList();

    /**
     * Get the 'interacted' statements from LRS based on filters
     * Filters the ones that have the results
     * 
     * @param array $data An array of filters.
     * @throws GeneralException
     * @return array
     */
    public function getInteractedResultStatements(array $data);

    /**
     * Get summary of an 'interacted' (non-scoring) statement
     * 
     * @param Statement $statement A TinCan API statement object.
     * @return array
     */
    public function getNonScoringStatementSummary(Statement $statement);

    /**
     * Get 'submitted-curriki' statements from LRS based on filters
     *
     * @param array $data An array of filters.
     * @param int $limit The number of statements to fetch
     * @throws GeneralException
     * @return array
     */
    public function getSubmittedCurrikiStatements(array $data, int $limit = 0);

    /**
     * Find 'attempt iri' from the list
     * 
     * @param array $other The list of activity IRIs
     * 
     * @return string
     */
    public function findAttemptIRI(array $other);

    /**
     * Find grouping info from the list
     * 
     * @param array $other The list of activity IRIs
     * 
     * @return array
     */
    public function findGroupingInfo(array $other);

    /**
     * Get Verb from statement
     * 
     * @param Verb $verb A TinCan API verb object.
     * 
     * @return array
     */
    public function getVerbFromStatement(Verb $verb);

    /**
     * Get H5P Interaction from category
     * 
     * @param array An array of Category IRIs
     * 
     * @return string
     */
    public function getH5PInterationFromCategory($category);

    /**
     * Is interaction one of the allowed aggregates?
     * 
     * @return bool
     */
    public function isAllowedAggregateH5P($interaction);

    /**
     * Retrieve a specific object extension from a list of extensions in an Activity definition.
     * 
     * @param ActivityDefinition $definition An Activity Defintion object.
     * @param string $needle A extension IRI to look for.
     * @return string
     */
    public function getExtensionValueFromList(ActivityDefinition $definition, $needle);

    /**
     * Get the 'completed' statements for aggregatecontent-types from LRS based on filters
     * 
     * @param array $data An array of filters.
     * @throws GeneralException
     * @return array
     */
    public function getAggregatesCompletedStatements(array $data);

    /**
     * Get SubContentId from category
     * 
     * @param array An array of Parent IRIs
     * 
     * @return string
     */
    public function getParentSubContentId($parent);

    /**
     * Join Parent's & Child's sub content ids
     * 
     * @param string $parentId
     * @param string $childId
     * 
     * @return string
     */
    public function joinParentChildSubContentIds($parentId, $childId);

}
