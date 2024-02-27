<?php

namespace App\Services\C2E\Publisher;

use App\Models\IndependentActivity;
use App\User;
use App\Models\C2E\Publisher\Publisher;

/**
 * Interface for the Generic Publisher Service
 */
interface PublisherServiceInterface
{
    /**
     * Publishes an independent activity to the target publisher store
     *
     * @param User $user
     * @param Publisher $publisher
     * @param IndependentActivity $independentActivity
     * @param $storeId
     *
     * @return mixed
     */
	public function publishIndependentActivity(
		User $user,
		Publisher $publisher,
		IndependentActivity $independentActivity,
		$storeId
    );

	/**
     * Generate independent activity payload
     *
     * @param User $user
     * @param IndependentActivity $independentActivity
     * @param $storeId
     *
     * @return string
     */
	public function generatePayload(User $user, IndependentActivity $independentActivity, $storeId);

	/**
     * Generate independent activity payload workflowItems
     *
     * @param IndependentActivity $independentActivity
     *
     * @return array
     */
	public function generateWorkflowItems(IndependentActivity $independentActivity);

	/**
     * Extract independent activity payload media
     *
     * @param IndependentActivity $independentActivity
     * @param $h5pContentsParameters
     * @param $mediaArray
     *
     * @return array
     */
	public function extractMedia(IndependentActivity $independentActivity, $h5pContentsParameters, $mediaArray);

	/**
     * Extract independent activity payload media data
     *
     * @param IndependentActivity $independentActivity
     * @param $mediaContent
     *
     * @return array
     */
	public function extractMediaData(IndependentActivity $independentActivity, $mediaContent);

	/**
     * Publishes a payload to the publisher
     *
     * @param Publisher $publisher
     * @param $payload
     *
     * @return mixed
     */
	public function publishC2e(Publisher $publisher, $payload);

	/**
     * Get publisher stores
     *
     * @param Publisher $publisher
     *
     * @return mixed
     */
	public function getStores(Publisher $publisher);
     
	/**
     * Get publisher stores
     *
     * @param Publisher $publisher
     * @param String $token
     * @param String $ceeId
     *
     * @return mixed
     */
     public function verifyC2EToken(Publisher $publisher, $token, $ceeId);
}
