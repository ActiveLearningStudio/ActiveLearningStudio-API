<?php

namespace App\Repositories\C2E\Publisher;

use App\Models\C2E\Publisher\Publisher;
use App\Models\IndependentActivity;
use App\Models\Organization;
use App\User;
use App\Repositories\BaseRepository;
use App\Repositories\C2E\Publisher\PublisherRepositoryInterface;
use App\Services\C2E\Publisher\PublisherServiceInterface;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Log;

class PublisherRepository extends BaseRepository implements PublisherRepositoryInterface
{
    private $publisherService;

    /**
     * PublisherRepository constructor
     *
     * @param Publisher $model
     * @param PublisherServiceInterface $publisherService
     */
    public function __construct(Publisher $model, PublisherServiceInterface $publisherService)
    {
        parent::__construct($model);
        $this->publisherService = $publisherService;
    }

    /**
     * Get all publishers
     *
     * @param array $data
     * @param Organization $suborganization
     * 
     * @return mixed
     */
    public function getAll($data, $suborganization)
    {
        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');
        $query = $this->model;

        if (isset($data['query']) && $data['query'] !== '') {
            $query = $query->whereHas('user', function ($qry) use ($data) {
                $qry->where('first_name', 'iLIKE', '%' . $data['query'] . '%');
                $qry->orWhere('last_name', 'iLIKE', '%' . $data['query'] . '%');
                $qry->orWhere('email', 'iLIKE', '%' . $data['query'] . '%');
                $qry->orwhere('url', 'iLIKE', '%' . $data['query'] . '%');
                $qry->orWhere('name', 'iLIKE', '%' . $data['query'] . '%');
            });
        }

        if (isset($data['order_by_column']) && $data['order_by_column'] !== '')
        {
            $orderByType = isset($data['order_by_type']) ? $data['order_by_type'] : 'ASC';
            $query = $query->orderBy($data['order_by_column'], $orderByType);
        }

        if (isset($data['filter']) && $data['filter'] !== '') {
            $query = $query->where('name', $data['filter']);
        }

        return $query->with(['user', 'organization'])
                     ->where('organization_id', $suborganization->id)
                     ->paginate($perPage)->withQueryString();
    }

    /**
     * Create publishers
     *
     * @param array $data
     * 
     * @return mixed
     * 
     * @throws GeneralException
     */
    public function create($data)
    {
        try {
            if ($setting = $this->model->create($data)) {
                return ['message' => 'Setting created successfully!', 'data' => $setting];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to create setting, please try again later!');
    }

    /**
     * Update publishers
     *
     * @param Publisher $publisher
     * @param array $data
     * 
     * @return mixed
     * 
     * @throws GeneralException
     */
    public function update($publisher, $data)
    {
        try {
            if ($publisher->update($data)) {
                return ['message' => 'Setting updated successfully!', 'data' => $this->find($publisher->id)];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to update setting, please try again later!');
    }

    /**
     * Find publisher by id
     *
     * @param int $id
     * 
     * @return mixed
     * 
     * @throws GeneralException
     */
    public function find($id)
    {
        if ($setting = $this->model->find($id)) {
            return $setting;
        }
        throw new GeneralException('Setting not found.');
    }

    /**
     * Delete publisher
     *
     * @param Publisher $publisher
     * 
     * @return mixed
     * 
     * @throws GeneralException
     */
    public function destroy($publisher)
    {
        try {
            $publisher->delete();
            return 'Publisher setting deleted successfully!';
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to delete setting, please try again later!');
    }

    /**
     * Fetch all publishers by user id
     *
     * @param int $userId
     */
    public function fetchAllByUserId($userId)
    {
        return $this->model->where(['user_id' => $userId])->get();
    }

    /**
     * Publish independent activity to store
     *
     * @param User $user
     * @param Publisher $publisher
     * @param IndependentActivity $independentActivity
     * @param int $storeId
     * @throws GeneralException
     */
    public function publishIndependentActivity(User $user, Publisher $publisher, IndependentActivity $independentActivity, $storeId)
    {
        return $this->publisherService->publishIndependentActivity($user, $publisher, $independentActivity, $storeId);
    }

    /**
     * Get Publisher Stores
     *
     * @param Publisher $publisher
     * @throws GeneralException
     */
    public function getStores(Publisher $publisher)
    {
        return $this->publisherService->getStores($publisher);
    }
}
