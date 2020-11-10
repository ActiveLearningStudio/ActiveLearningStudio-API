<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\XapiStatementRequest;
use Illuminate\Http\Request;
use App\Services\LearnerRecordStoreService;

/**
 * @group 16. XAPI
 *
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
            $response = $service->saveStatement($data['statement']);
            if ($response->success) {
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
            return response([
                'errors' => ["The statement could not be saved due to an error: " . $e->getMessage()],
            ], 500);
        }
    }
}
