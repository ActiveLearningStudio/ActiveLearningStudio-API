<?php

namespace App\Repositories\Organization;

use App\Models\Organization;
use App\Repositories\EloquentRepositoryInterface;

interface OrganizationRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * To fetch suborganizations
     *
     * @param $parent_id
     * @return Organization $organizations
     */
    public function fetchSuborganizations($parent_id);

    /**
     * To create a suborganization
     *
     * @param $data
     */
    public function createSuborganization($data);

    /**
     * To delete a suborganization
     *
     * @param $id
     */
    public function deleteSuborganization($id);

    /**
     * To get the users to add in specific suborganization
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function getMemberOptions($data, $id);

    /**
     * Add user for the specified role in default suborganization
     *
     * @param $id
     * @param array $data
     * @return Model
     */
    public function addUser($id, $data);

    /**
     * Update user for the specified role in particular suborganization
     *
     * @param $id
     * @param array $data
     * @return Model
     */
    public function updateUser($id, $data);

    /**
     * Delete the specified user in a particular suborganization
     *
     * @param $id
     * @param array $data
     * @return Model
     */
    public function deleteUser($id, $data);

    /**
     * To fetch organization users
     *
     * @param $id
     * @return Model
     */
    public function fetchOrganizationUsers($id);
}
