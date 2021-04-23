<?php

namespace App\Repositories\CurrikiGo\Outcome;

use App\Repositories\CurrikiGo\Outcome\OutcomeRepositoryInterface;
use App\Http\Resources\V1\CurrikiGo\StudentResultResource;
use App\CurrikiGo\H5PLibrary\H5PLibraryFactory;
use Djoudi\LaravelH5p\Eloquents\H5pContent;
use App\Services\LearnerRecordStoreService;

class OutcomeRepository implements OutcomeRepositoryInterface
{

    // Returns several project metrics for the specified user
    public function getStudentOutcome($actor, $activity) {
        $result = $this->getStudentOutcomeData(compact('actor', 'activity'));

        if (isset($result['errors'])) {
            return $result;
        }

        // Normalize responses for frontend consumption
        $normalizedResult = $this->normalizeRow($result['summary']);
        return ['summary' => $normalizedResult];
    }

    private function normalizeRow($data) {
        // We're at the top level
        if ($this->checkArr($data)) {
            $result = [];

            foreach($data as $row) {
                $result[] = $this->normalizeRow($row);
            }

            return $result;
        }

        // This is an activity aggregator like column or questionnaire
        if (isset($data['title']) && isset($data['content']) && $this->checkArr($data['content'])) {
            $deeperResult = [];

            foreach ($data['content'] as $row) {
                $deeperResult[] = $this->normalizeRow($row);
            }

            return [
                'type' => 'section',
                'content_type' => $data['content-type'],
                'sub_content_id' => $data['sub-content-id'],
                'title' => $data['title'],
                'children' => $deeperResult,
            ];
        }

        // This is a question
        if (isset($data['answer'])) {
            $answers = [];

            foreach ($data['answer'] as $answer) {
                if (isset($answer['response']) && is_array($answer['response'])) {
                    $answers[] = [
                        'score' => $answer['score'],
                        'response' => $answer['response'],
                    ];
                } elseif (isset($answer['response'])) {
                    $answers[] = [
                        'score' => $answer['score'],
                        'response' => [$answer['response']],
                    ];
                } else {
                    $answers[] = ['score' => $answer['score']];
                }
            }

            return [
                'type' => 'question',
                'content_type' => $data['content-type'],
                'sub_content_id' => $data['sub-content-id'],
                'title' => $data['title'],
                'answers' => $answers,
            ];
        }
        // Unknown case
        return [
            'type' => 'question',
            'content_type' => $data['content-type'],
            'sub_content_id' => $data['sub-content-id'],
            'title' => $data['title'],
            'answers' => [['response' => 'N/A', 'score' => ['max' => 0, 'raw' => 0]]],
        ];
    }

    // Checks if its a normal array or a statement object
    private function checkArr($array) {
        return count(array_filter(array_keys($array), 'is_string')) === 0;
    }

    private function getStudentOutcomeData($data) {
        $response = [];
        try {
            $service = new LearnerRecordStoreService();
            $submitted = $service->getSubmittedCurrikiStatements($data, 1);
            
            if (count($submitted) > 0) {
                // Get 'other' activity IRI from the statement
                // that now has the unique context of the attempt.
                $attemptIRI = '';
                // Get H5P Content ID:
                $h5pContent = '';
                $categoryId = '';
                $h5pInteraction = '';
                foreach ($submitted as $statement) {
                    $contextActivities = $statement->getContext()->getContextActivities();
                    $other = $contextActivities->getOther();
                    // Get the attempt IRI
                    $attemptIRI = $service->findAttemptIRI($other);
                    $target = $statement->getTarget();
                    $category = $contextActivities->getCategory();
                    
                    if (!empty($category)) {
                        $categoryId = end($category)->getId();
                        $h5pInteraction = explode("/", $categoryId);
                        $h5pInteraction = end($h5pInteraction);
                    }

                    // At the moment, we're only tackling targets of 'activity' type.
                    $definition = ($target->getObjectType() === 'Activity' ? $target->getDefinition() : '');
                    // Extract information from object.definition.extensions
                    if ($target->getObjectType() === 'Activity' && !empty($definition)) {
                        $h5pContentId = $service->getExtensionValueFromList($definition, LearnerRecordStoreService::EXTENSION_H5P_LOCAL_CONTENT_ID);
                        $h5pContent = H5pContent::findOrFail($h5pContentId);
                    }
                }
                
                if (!empty($attemptIRI) && !empty($h5pContent)) {
                    $contentParams = json_decode($h5pContent->parameters, true); 
                    $h5pFactory = new H5PLibraryFactory();
                    $h5pLib = $h5pFactory->init($h5pInteraction, $contentParams);
                    
                    if ($h5pLib) {
                        $h5pMeta = $h5pLib->buildMeta();
                    }
                   
                    // UPDATE: We want to accumulate all responses, and each attempt is not a unique attempt anymore.
                    // So, we just check for an attempt, and then keep the search by submission id.
                    // $data['activity'] = $attemptIRI;
                    $answers = $service->getLatestAnsweredStatementsWithResults($data);
                    $answeredIds = [];
                    if ($answers) {
                        $answeredIds = array_keys($answers);
                        foreach ($answers as $key => $record) {
                            $summary = $service->getStatementSummary($record);
                            $summaryRes = new StudentResultResource($summary);
                            $response[] = $summaryRes;
                            recursive_array_search_insert($key, $h5pMeta, $summaryRes);
                        }
                    }
                    
                    // Find any interacted/attempted interactions as well
                    $attempted = $service->getAttemptedStatements($data);
                    if ($attempted) {
                        foreach ($attempted as $key => $record) {
                            if (!in_array($key, $answeredIds)) {
                                $summary = $service->getStatementSummary($record);
                                $summaryRes = new StudentResultResource($summary);
                                $response[] = $summaryRes;
                                $answeredIds[] = $key;
                                recursive_array_search_insert($key, $h5pMeta, $summaryRes);
                            }
                        }
                    }
                   
                    // Get Non-scoring Interactions
                    $nonScoringResponse = [];
                    $interacted = $service->getInteractedResultStatements($data);
                    $interactedIds = [];
                    //print_r(array_keys($interacted));
                    if ($interacted) {
                        $inconsistentKeys = [];
                        foreach ($interacted as $key => $record) {
                            // Find if the key has a hash for description as well.
                            $position = strpos($key, '::');
                            if (!in_array($key, $answeredIds) || $position !== FALSE) {
                                $summary = $service->getNonScoringStatementSummary($record);
                                $summaryRes = new StudentResultResource($summary); 
                                $nonScoringResponse[] = $summaryRes;
                                $newKey = $key;
                                if ($position !== FALSE) {
                                    $key = substr($key, 0, $position);
                                    $inconsistentKeys[] = $key;
                                } else {
                                    $interactedIds[] = $key;
                                }
                                recursive_array_search_insert($key, $h5pMeta, $summaryRes);
                            }
                        }
                        if (!empty($inconsistentKeys)) {
                            $interactedIds = array_merge($interactedIds, array_unique($inconsistentKeys));
                        }
                        if (!empty($interactedIds)) {
                            $answeredIds = array_merge($interactedIds, array_unique($answeredIds));
                        }
                    }
                    
                    // Check for any aggregates completed statements, before finalizing skipped
                    // This happens for Quesionnaire, Interactive Video, Course Presentation
                    $aggregateCompleted = $service->getAggregatesCompletedStatements($data);

                    if ($aggregateCompleted) {
                        foreach ($aggregateCompleted as $key => $record) {
                            if (!in_array($key, $answeredIds)) {
                                $answeredIds[] = $key;
                            }
                        }
                    }

                    // Find any skipped interactions as well
                    // Note: Non-scoring activities such as 'quesitonnaire' triggers a skipped as well.
                    // We need to account for that when reporting skipped.
                    $skipped = $service->getSkippedStatements($data);
                    if ($skipped) {
                        foreach ($skipped as $key => $record) {
                            if (!in_array($key, $answeredIds)) {
                                $summary = $service->getStatementSummary($record);
                                $summaryRes = new StudentResultResource($summary); 
                                $response[] = $summaryRes;
                                $answeredIds[] = $key;
                                recursive_array_search_insert($key, $h5pMeta, $summaryRes);
                            }
                        }
                    }
                   
                    return [
                        'summary' => $h5pMeta,
                    ];
                } else {
                    return [
                        'errors' => ["No results found."],
                    ];
                }
            } else {
                return [
                    'errors' => ["No results found."],
                ];
            }
        } catch (Exception $e) {
            return [
                'errors' => ["The outcome could not be retrived: " . $e->getMessage()],
            ];
        }
    }
}
