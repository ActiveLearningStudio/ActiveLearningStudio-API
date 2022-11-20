<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\SearchAuthorTagRequest;
use App\Http\Requests\V1\StoreAuthorTagRequest;
use App\Http\Requests\V1\UpdateAuthorTagRequest;
use App\Http\Resources\V1\AuthorTagResource;
use App\Models\AuthorTag;
use App\Models\Organization;
use App\Repositories\AuthorTag\AuthorTagRepositoryInterface;
use Illuminate\Http\Response;

/**
 * @group 23. Author Tag
 *
 * APIs for author tags management
 */
class AuthorTagController extends Controller
{
    private $authorTagRepository;

    /**
     * AuthorTagController constructor.
     *
     * @param AuthorTagRepositoryInterface $authorTagRepository
     */
    public function __construct(AuthorTagRepositoryInterface $authorTagRepository) {
        $this->authorTagRepository = $authorTagRepository;
    }

    /**
     * Get Author Tags
     *
     * Get a list of all author tags.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * 
     * @responseFile responses/author-tag/author-tags.json
     *
     * @param SearchAuthorTagRequest $request
     * @param Organization $suborganization
     *
     * @return Response
     */
    public function index(SearchAuthorTagRequest $request, Organization $suborganization)
    {
        return  AuthorTagResource::collection(
            $this->authorTagRepository->getAll($suborganization, $request->all())
        );
    }

    /**
     * Create Author Tag
     *
     * Create a new author tag.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam name string required The name of a author tag Example: Grade A
     * @bodyParam order int The order number of a author tag item Example: 1
     *
     * @responseFile 201 responses/author-tag/author-tag.json
     *
     * @response 500 {
     *   "errors": [
     *     "Could not create author tag. Please try again later."
     *   ]
     * }
     *
     * @param StoreAuthorTagRequest $request
     * @param Organization $suborganization
     *
     * @return Response
     */
    public function store(StoreAuthorTagRequest $request, Organization $suborganization)
    {
        $data = $request->validated();
        $authorTag = $this->authorTagRepository->create($data);

        if ($authorTag) {
            return response([
                'author-tag' => new AuthorTagResource($authorTag),
            ], 201);
        }

        return response([
            'errors' => ['Could not create author tag. Please try again later.'],
        ], 500);
    }

    /**
     * Get Author Tag
     *
     * Get the specified author tag.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam authorTag required The Id of a Author Tag item Example: 1
     *
     * @responseFile responses/author-tag/author-tag.json
     *
     * @param Organization $suborganization
     * @param AuthorTag $authorTag
     *
     * @return Response
     */
    public function show(Organization $suborganization, AuthorTag $authorTag)
    {
        if ($suborganization->id !== $authorTag->organization_id) {
            return response([
                'message' => 'Invalid author tag or organization',
            ], 400);
        }

        return response([
            'author-tag' => new AuthorTagResource($authorTag),
        ], 200);
    }

    /**
     * Update Author Tag
     *
     * Update the specified author tag.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam authorTag required The Id of a author tag Example: 1
     * @bodyParam name string required The name of a author tag Example: Audio
     * @bodyParam order int The order number of a author tag item Example: 1

     * @responseFile responses/author-tag/author-tag.json
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to update author tag."
     *   ]
     * }
     *
     * @param UpdateAuthorTagRequest $request
     * @param Organization $suborganization
     * @param AuthorTag $authorTag
     *
     * @return Response
     */
    public function update(UpdateAuthorTagRequest $request, Organization $suborganization,
        AuthorTag $authorTag)
    {
        $data = $request->validated();
        $isUpdated = $this->authorTagRepository->update($data, $authorTag->id);

        if ($isUpdated) {
            return response([
                'author-tag' => new AuthorTagResource($this->authorTagRepository->find($authorTag->id)),
            ], 200);
        }

        return response([
            'errors' => ['Failed to update author tag.'],
        ], 500);
    }

    /**
     * Remove Author Tag
     *
     * Remove the specified author tag.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam authorTag required The Id of a author tag item Example: 1
     *
     * @response {
     *   "message": "Author Tag has been deleted successfully."
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to delete author tag."
     *   ]
     * }
     *
     * @param Organization $suborganization
     * @param AuthorTag $authorTag
     *
     * @return Response
     */
    public function destroy(Organization $suborganization, AuthorTag $authorTag)
    {
        if ($suborganization->id !== $authorTag->organization_id) {
            return response([
                'message' => 'Invalid author tag or organization',
            ], 400);
        }

        $isDeleted = $this->authorTagRepository->delete($authorTag->id);

        if ($isDeleted) {
            return response([
                'message' => 'Author tag has been deleted successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to delete author tag.'],
        ], 500);
    }
}
