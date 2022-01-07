<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\SearchEducationLevel;
use App\Http\Requests\V1\StoreEducationLevel;
use App\Http\Requests\V1\UpdateEducationLevel;
use App\Http\Requests\V1\UpdateSubject;
use App\Http\Resources\V1\EducationLevelResource;
use App\Http\Resources\V1\SubjectResource;
use App\Models\EducationLevel;
use App\Repositories\EducationLevel\EducationLevelRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group 7. Education Level
 *
 * APIs for education level management
 */
class EducationLevelController extends Controller
{
    private $educationLevelRepository;

    /**
     * EducationLevelController constructor.
     *
     * @param EducationLevelRepositoryInterface $educationLevelRepository
     */
    public function __construct(EducationLevelRepositoryInterface $educationLevelRepository) {
        $this->educationLevelRepository = $educationLevelRepository;
    }

    /**
     * Get Education Level
     *
     * Get a list of all education level.
     *
     * @responseFile responses/education-level/education-levels.json
     *
     * @param SearchEducationLevel $request
     * @return Response
     */
    public function index(SearchEducationLevel $request)
    {
        return  EducationLevelResource::collection($this->educationLevelRepository->getAll($request->all()));
    }

    /**
     * Create Education Level
     *
     * Create a new education level.
     *
     * @bodyParam name string required The name of a education level Example: Grade A
     * @bodyParam order int The order number of a education level item Example: 1
     *
     * @responseFile 201 responses/education-level/education-level.json
     *
     * @response 500 {
     *   "errors": [
     *     "Could not create education level. Please try again later."
     *   ]
     * }
     *
     * @param StoreEducationLevel $request
     * @return Response
     */
    public function store(StoreEducationLevel $request)
    {
        $data = $request->validated();
        $educationLevel = $this->educationLevelRepository->create($data);

        if ($educationLevel) {
            return response([
                'education-level' => new EducationLevelResource($educationLevel),
            ], 201);
        }

        return response([
            'errors' => ['Could not create education level. Please try again later.'],
        ], 500);
    }

    /**
     * Get Education Level
     *
     * Get the specified education level.
     *
     * @urlParam EducationLevel required The Id of a Education Level item Example: 1
     *
     * @responseFile responses/education-level/education-level.json
     *
     * @param EducationLevel $educationLevel
     * @return Response
     */
    public function show(EducationLevel $educationLevel)
    {
        return response([
            'education-level' => new EducationLevelResource($educationLevel),
        ], 200);
    }

    /**
     * Update Education Level
     *
     * Update the specified education level.
     *
     * @urlParam educationLevel required The Id of a education level Example: 1
     * @bodyParam name string required The name of a education level Example: Grade A
     * @bodyParam order int The order number of a education level item Example: 1

     * @responseFile responses/education-level/education-level.json
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to update education level."
     *   ]
     * }
     *
     * @param Request $request
     * @param EducationLevel $educationLevel
     * @return Response
     */
    public function update(UpdateEducationLevel $request, EducationLevel $educationLevel)
    {
        $data = $request->validated();
        $isUpdated = $this->educationLevelRepository->update($data, $educationLevel->id);

        if ($isUpdated) {
            return response([
                'education-level' => new EducationLevelResource($this->educationLevelRepository->find($educationLevel->id)),
            ], 200);
        }

        return response([
            'errors' => ['Failed to update education level.'],
        ], 500);
    }

    /**
     * Remove Education Level
     *
     * Remove the specified education level.
     *
     * @urlParam educationLevel required The Id of a education level item Example: 1
     *
     * @response {
     *   "message": "Education Level has been deleted successfully."
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to delete education level."
     *   ]
     * }
     *
     * @param EducationLevel $educationLevel
     * @return Response
     */
    public function destroy(EducationLevel $educationLevel)
    {
        $isDeleted = $this->educationLevelRepository->delete($educationLevel->id);

        if ($isDeleted) {
            return response([
                'message' => 'Education level has been deleted successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to delete education level.'],
        ], 500);
    }
}
