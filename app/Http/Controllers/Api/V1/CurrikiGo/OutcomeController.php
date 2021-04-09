<?php

namespace App\Http\Controllers\API\V1\CurrikiGo;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CurrikiGo\GetStudentResultRequest;
use App\Http\Resources\V1\CurrikiGo\StudentResultResource;
use Illuminate\Http\Request;
use App\Services\LearnerRecordStoreService;
use App\CurrikiGo\H5PLibrary\H5PLibraryFactory;
use Djoudi\LaravelH5p\Eloquents\H5pContent;

/**
 * @group 15. CurrikiGo Outcome
 *
 * APIs for generating outcomes against students' submissions.
 */
class OutcomeController extends Controller
{
    /**
     * Get Student Results Summary
     *
     * Fetch LRS statements based on parameters, and generate a student result summary
     *
     * @param GetStudentResultRequest $studentResultRequest
     *
     * @responseFile responses/outcome/student-result-summary.json
     *
     * @response 404 {
     *   "errors": [
     *     "No results found."
     *   ]
     * }
     *
     * @param GetStudentResultRequest $studentResultRequest
     * @return Response
     */
    public function getStudentResultSummary(GetStudentResultRequest $studentResultRequest)
    {
        $data = $studentResultRequest->validated();
        $response = [];
        try {
            $service = new LearnerRecordStoreService();
            $submitted = $service->getSubmittedCurrikiStatements($data, 1);
            
            if (count($submitted) > 0) {
                // Get 'other' activity IRI from the statement
                // that now has the unique context of the attempt.
                $attemptIRI = '';
                foreach ($submitted as $statement) {
                    $contextActivities = $statement->getContext()->getContextActivities();
                    $other = $contextActivities->getOther();
                    // Get the attempt IRI
                    $attemptIRI = $service->findAttemptIRI($other);
                }
                if (!empty($attemptIRI)) {
                    // UPDATE: We want to accumulate all responses, and each attempt is not a unique attempt anymore.
                    // So, we just check for an attempt, and then keep the search by submission id.
                    // $data['activity'] = $attemptIRI;
                    $answers = $service->getLatestAnsweredStatementsWithResults($data);
                    $answeredIds = [];
                    if ($answers) {
                        $answeredIds = array_keys($answers);
                        foreach ($answers as $record) {
                            $summary = $service->getStatementSummary($record);
                            $response[] = new StudentResultResource($summary);
                        }
                    }
                    
                    // Find any interacted/attempted interactions as well
                    $attempted = $service->getAttemptedStatements($data);
                    if ($attempted) {
                        foreach ($attempted as $key => $record) {
                            if (!in_array($key, $answeredIds)) {
                                $summary = $service->getStatementSummary($record);
                                $response[] = new StudentResultResource($summary);
                                $answeredIds[] = $key;
                            }
                        }
                    }
                   
                    // Get Non-scoring Interactions
                    $nonScoringResponse = [];
                    $interacted = $service->getInteractedResultStatements($data);

                    if ($interacted) {
                        foreach ($interacted as $key => $record) {
                            if (!in_array($key, $answeredIds)) {
                                $summary = $service->getNonScoringStatementSummary($record);
                                $nonScoringResponse[] = new StudentResultResource($summary);
                                $answeredIds[] = $key;
                            }
                        }
                    }
                    // Since we usually do not have ending-point for most non-scoring items, and 
                    // since normally the LRS will return the oldest statements first
                    // we want to reverse the order of the statements to show on the summary page
                    // so it's first in, first out.
                    if (!empty($nonScoringResponse)) {
                        $nonScoringResponse = array_reverse($nonScoringResponse);
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
                                $response[] = new StudentResultResource($summary);
                                $answeredIds[] = $key;
                            }
                        }
                    }

                    // We'll use the ending-point for ordering the final results.
                    usort($response, function($a, $b) {
                        return $a['ending-point'] <=> $b['ending-point'];
                    });
                    
                    return response([
                        'summary' => $response,
                        'non-scoring' => $nonScoringResponse
                    ], 200);
                } else {
                    return response([
                        'errors' => ["No results found."],
                    ], 404);
                }
            } else {
                return response([
                    'errors' => ["No results found."],
                ], 404);
            }
        } catch (Exception $e) {
            return response([
                'errors' => ["The outcome could not be retrived: " . $e->getMessage()],
            ], 500);
        }
    }

    /**
     * Get Student Results Grouped Summary 
     *
     * Fetch LRS statements based on parameters, and generate a student result summary
     *
     * @param GetStudentResultRequest $studentResultRequest
     *
     * @responseFile responses/outcome/student-result-summary.json
     *
     * @response 404 {
     *   "errors": [
     *     "No results found."
     *   ]
     * }
     *
     * @param GetStudentResultRequest $studentResultRequest
     * @return Response
     */
    public function getStudentResultGroupedSummary(GetStudentResultRequest $studentResultRequest)
    {
        $data = $studentResultRequest->validated();
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
                        //$h5pContent = H5pContent::findOrFail(19591);//19581);//7);//38074); //19590
                        /*var_dump($h5pContent);
                        exit;
                        print_r($h5pContent->parameters);
                        exit;*/
                    }
                }
                
                if (!empty($attemptIRI) && !empty($h5pContent)) {
                    $contentParams = json_decode($h5pContent->parameters, true); 
                    $h5pFactory = new H5PLibraryFactory();
                    $h5pLib = $h5pFactory->init($h5pInteraction, $contentParams);
                    
                    if ($h5pLib) {
                        $h5pMeta = $h5pLib->buildMeta();
                    }
                   /* echo '<pre>';
                    echo(json_encode($h5pMeta));
                    exit;*/
                   
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
                    //print_r($answeredIds);
                    
                    // Since we usually do not have ending-point for most non-scoring items, and 
                    // since normally the LRS will return the oldest statements first
                    // we want to reverse the order of the statements to show on the summary page
                    // so it's first in, first out.
                    if (!empty($nonScoringResponse)) {
                        $nonScoringResponse = array_reverse($nonScoringResponse);
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
                   
                    // We'll use the ending-point for ordering the final results.
                    usort($response, function($a, $b) {
                        return $a['ending-point'] <=> $b['ending-point'];
                    });

                    return response([
                        'summary' => $response,
                        'non-scoring' => $nonScoringResponse,
                        'h5p' => $h5pMeta
                    ], 200);
                } else {
                    return response([
                        'errors' => ["No results found."],
                    ], 404);
                }
            } else {
                return response([
                    'errors' => ["No results found."],
                ], 404);
            }
        } catch (Exception $e) {
            return response([
                'errors' => ["The outcome could not be retrived: " . $e->getMessage()],
            ], 500);
        }
    }

    

}
