<?php

namespace App\Repositories\IndependentActivity;

use App\Models\Organization;
use App\Models\IndependentActivity;
use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Support\Collection;

interface IndependentActivityRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get the advance search request
     *
     * @param array $data
     * @param int $authUser
     * @return Collection
     */
    public function advanceSearchForm($data, $authUser = null);

    /**
     * To clone Independent Activity
     * 
     * @param Organization $organization
     * @param IndependentActivity $independentActivity
     * @param string $token
     * @return int
     */
    public function clone(Organization $organization, IndependentActivity $independentActivity, $token);

    /**
     * Get latest order of independent activity for Organization
     * @param $organizationId
     * @return int
     */
    public function getOrder($organizationId);

    /**
     * Create model in storage
     *
     * @param $authenticatedUser
     * @param $suborganization
     * @param $data
     * @return Model
     */
    public function createIndependentActivity($authenticatedUser, $suborganization, $data);

    /**
     * Get user independent activity ids in org
     *
     * @param $authenticatedUser
     * @param $organization
     * @return array
     */
    public function getUserIndependentActivityIdsInOrganization($authenticatedUser, $organization);

    /**
     * @param $data
     * @param $suborganization
     * @return mixed
     */
    public function getAll($data, $suborganization);

    /**
     * To export independent activity 
     *
     * @param $authUser
     * @param IndependentActivity $independent_activity
     * @param int $suborganization_id
     * @throws GeneralException
     */
    public function exportIndependentActivity($authUser, IndependentActivity $independent_activity);

    /**
     * To import independent activity 
     *
     * @param $authUser
     * @param $path
     * @param int $suborganization_id
     * @throws GeneralException
     */
    public function importIndependentActivity($authUser, $path, $suborganization_id, $method_source="API");

    /**
     * Update Indexes for independent activities and related models
     * @param $independentActivity
     * @param $index
     * @return string
     * @throws GeneralException
     */
    public function updateIndex($independentActivity, $index);

    /**
     * Copy Exisiting independentent activity into a playlist
     * @param $independentActivity
     * @param $playlist
     * @param $token
     * @return string
     * 
     */
    public function copyToPlaylist( $independentActivity, $playlist, $token);
}
