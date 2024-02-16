<?php

namespace App\Repositories\C2E\Publisher;

use App\Models\C2E\Publisher\Publisher;
use App\Models\IndependentActivity;
use App\Models\Organization;
use App\User;
use App\Exceptions\GeneralException;
use App\Repositories\EloquentRepositoryInterface;

interface PublisherRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get all publishers
     *
     * @param array $data
     * @param Organization $suborganization
     * 
     * @return mixed
     */
    public function getAll($data, $suborganization);

    /**
     * Create publishers
     *
     * @param array $data
     * 
     * @return mixed
     * 
     * @throws GeneralException
     */
    public function create($data);

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
    public function update($publisher, $data);

    /**
     * Find publisher by id
     *
     * @param int $id
     * 
     * @return mixed
     * 
     * @throws GeneralException
     */
    public function find($id);

    /**
     * Delete publisher
     *
     * @param Publisher $publisher
     * 
     * @return mixed
     * 
     * @throws GeneralException
     */
    public function destroy($publisher);

    /**
     * Fetch all publishers by user id
     *
     * @param int $userId
     */
    public function fetchAllByUserId($userId);

    /**
     * Publish independent activity to store
     *
     * @param User $user
     * @param Publisher $publisher
     * @param IndependentActivity $independentActivity
     * @param int $storeId
     * @throws GeneralException
     */
    public function publishIndependentActivity(User $user, Publisher $publisher, IndependentActivity $independentActivity, $storeId);

    /**
     * Get Publisher Stores
     *
     * @param Publisher $publisher
     * @throws GeneralException
     */
    public function getStores(Publisher $publisher);
}
