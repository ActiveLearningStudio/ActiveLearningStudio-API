<?php

namespace App\Http\Controllers\API\V1\CurrikiGo;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CurrikiGo\GetStudentResultRequest;
use App\Http\Resources\V1\CurrikiGo\StudentResultResource;
use Illuminate\Http\Request;
use App\Services\LearnerRecordStoreService;

class OutcomeController extends Controller
{
    /**
     * Get Student Results Summary
     *
     * Fetch LRS statements based on parameters, and generate a student result summary
     *
     * @param GetStudentResultRequest $studentResultRequest
     *
     * @response 201 {
     *   "id": "61ffa986-1dcc-3df0-94d8-a09384b197a7"
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "The statement could not be saved due to an error"
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
                $answers = $service->getAnswersStatementsWithResults($data);
                // dd($answers);
                if ($answers) {
                    foreach($answers as $record) {
                        $summary = [];
                        $target = $record->getTarget();
                        $nameOfActivity = $target->getDefinition()->getName()->getNegotiatedLanguageString();
                        
                        $result = $record->getResult();
                        $summary['name'] = $nameOfActivity;
                        $summary['score'] = [
                            'raw' => $result->getScore()->getRaw(),
                            'max' => $result->getScore()->getMax(),
                        ];
                        $summary['duration'] = str_replace(array('PT', 'S'), '', $result->getDuration());
                        $response[] = $summary;
                    }
                }
                
                return response([
                    'summary' => $response,
                ], 201);
            }
            else {
                return response([
                    'errors' => ["No results found."],
                ], 500);
            }
        } catch (Exception $e) {
            return response([
                'errors' => ["The outcome could not be retrived: " . $e->getMessage()],
            ], 500);
        }
    }

}
