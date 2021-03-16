<?php

namespace App\Http\Controllers\API\V1\CurrikiGo;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CurrikiGo\GetStudentResultRequest;
use App\Http\Resources\V1\CurrikiGo\StudentResultResource;
use Illuminate\Http\Request;
use App\Services\LearnerRecordStoreService;
use App\CurrikiGo\H5PLibrary\H5PLibraryFactory;
use Djoudi\LaravelH5p\Eloquents\H5PContent;

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
                // Get H5P Content ID:

                // Get 'other' activity IRI from the statement
                // that now has the unique context of the attempt.
                $attemptIRI = '';
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
                        $h5pContent = H5PContent::findOrFail(19590);//19581);//7);//38074);
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
                    //echo '<pre>';
                    //print_r($h5pMeta);
                    //$needle = '558ea16f-6866-44f8-a822-5ef8b8b25c25';//'f6937923-bd5b-4c32-8de5-a9d1d8d28884';
                    //echo $key = recursive_array_search($needle, $h5pMeta);
                    //$myVal = $h5pMeta[11]['content'][3]['content'][0]['sub-content-id'];
                    //recursive_array_search_insert($needle, $h5pMeta, ['answer' => 'my answer']);
                    //print_r($h5pMeta);
                    //echo $myVal;
                    //exit('in here');
                    // UPDATE: We want to accumulate all responses, and each attempt is not a unique attempt anymore.
                    // So, we just check for an attempt, and then keep the search by submission id.
                    // $data['activity'] = $attemptIRI;
                    $answers = $service->getLatestAnsweredStatementsWithResults($data);
                    $answeredIds = [];
                    if ($answers) {
                        $answeredIds = array_keys($answers);
                        foreach ($answers as $key => $record) {
                            $summary = $service->getStatementSummary($record);
                            $response[] = new StudentResultResource($summary);
                            recursive_array_search_insert($key, $h5pMeta, ['answer' => $summary]);
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
                                recursive_array_search_insert($key, $h5pMeta, ['answer' => $summary]);
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
                                recursive_array_search_insert($key, $h5pMeta, ['answer' => $summary]);
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
                                recursive_array_search_insert($key, $h5pMeta, ['answer' => $summary]);
                            }
                        }
                    }
                    //print_r($h5pMeta);
                    //echo $myVal;
                    //exit('in here');
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
