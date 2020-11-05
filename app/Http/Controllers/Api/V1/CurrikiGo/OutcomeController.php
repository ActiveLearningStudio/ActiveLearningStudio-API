<?php

namespace App\Http\Controllers\API\V1\CurrikiGo;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CurrikiGo\GetStudentResultRequest;
use App\Http\Resources\V1\CurrikiGo\StudentResultResource;
use Illuminate\Http\Request;
use App\Services\LearnerRecordStoreService;

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
            $completed = $service->getCompletedStatements($data);
            if (count($completed) > 0) {
                // Assume that this statement already has a result
                $answers = $service->getLatestAnsweredStatementsWithResults($data);
                if ($answers) {
                    foreach ($answers as $record) {
                        $summary = $service->getStatementSummary($record);
                        $response[] = new StudentResultResource($summary);
                    }
                }
                // We'll use the ending-point for ordering the final results.
                usort($response, function($a, $b) {
                    return $a['ending-point'] <=> $b['ending-point'];
                });
                
                return response([
                    'summary' => $response,
                ], 200);
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
