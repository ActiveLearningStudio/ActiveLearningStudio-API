<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\XapiStatementRequest;
use Illuminate\Http\Request;
use App\Services\LearnerRecordStoreService;

/**
 * An XAPI Controller Class
 * Handles xAPI operations.
 */
class XapiController extends Controller
{
    /**
     * Save an xAPI Statement
     *
     * Creates a new statement in the database.
     *
     * @param XapiStatementRequest $statementRequest
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
     * @param XapiStatementRequest $statementRequest
     * @return Response
     */
    public function saveStatement(XapiStatementRequest $statementRequest)
    {
        $data = $statementRequest->validated();

        try {
            $service = new LearnerRecordStoreService();
            //dd($data);
            //$stringOfJSON = $data['statement'];//'{"actor":{"name":"Aqeel AAH","mbox":"mailto:aqeel.ahmad+curriki@tkxel.com","objectType":"Agent"},"verb":{"id":"http://adlnet.gov/expapi/verbs/answered","display":{"en-US":"answered"}},"object":{"id":"http://currikiapi.localhost/h5p/embed/19427","objectType":"Activity","definition":{"extensions":{"http://h5p.org/x-api/h5p-local-content-id":19427},"name":{"en-US":"Addition"}}},"context":{"contextActivities":{"category":[{"id":"http://h5p.org/libraries/H5P.ArithmeticQuiz-1.1","objectType":"Activity"}]}},"result":{"score":{"min":0,"max":2,"raw":2,"scaled":1},"completion":true,"duration":"PT81.94S"}}';
            //$statement = \TinCan\Statement::fromJSON($data['statement']);
            //dd($statement);

            $response = $service->saveStatement($data['statement']);
            if ($response->success) {
                //dd($response->content->getId());
                //print $response->content; // 'someValue'
                //print "Statement sent successfully!\n";
                return response([
                    'id' => $response->content->getId(),
                ], 201);
            }
            else {
                return response([
                    'errors' => ["Error: " . $response->content],
                ], 500);
            }
            
        } catch (Exception $e) {
            // echo $e->getMessage();
            return response([
                'errors' => ["The statement could not be saved due to an error: " . $e->getMessage()],
            ], 500);
        }
        
    }
}
