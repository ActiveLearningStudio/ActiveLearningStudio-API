<?php

namespace App\Services\C2E\Publisher;

use App\Services\C2E\Publisher\PublisherServiceInterface;
use App\Models\IndependentActivity;
use App\User;
use App\Models\C2E\Publisher\Publisher;
use Illuminate\Support\Facades\Http;

/**
 * Generic Publisher Service
 */
class PublisherService implements PublisherServiceInterface
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
	)
	{
		$payload = $this->generatePayload($user, $independentActivity, $storeId);
		return $this->publishC2e($publisher, $payload);
	}

	/**
     * Generate independent activity payload
     *
     * @param User $user
	 * @param IndependentActivity $independentActivity
	 * @param $storeId
     *
     * @return string
     */
	public function generatePayload(User $user, IndependentActivity $independentActivity, $storeId)
	{
		$payloadJson = [
			"name" => $independentActivity->title,
			"description" => $independentActivity->description,
			"creator" => [
				"name" => $user->name,
				"email" => $user->email
			],
			"storeId" => $storeId,
			"workflowItems" => $this->generateWorkflowItems($independentActivity)
		];
		
		return $payloadJson;
	}

	/**
     * Generate independent activity payload workflowItems
     *
     * @param IndependentActivity $independentActivity
     *
     * @return array
     */
	public function generateWorkflowItems(IndependentActivity $independentActivity)
	{
		$workflowItems = [];
		$subjects = [];
		$educationLevels = [];
		$keywords = [];

		foreach ($independentActivity->subjects as $subject) {
			$subjects[] = $subject->name;
		}

		foreach ($independentActivity->educationLevels as $educationLevel) {
			$educationLevels[] = $educationLevel->name;
		}

		foreach ($independentActivity->authorTags as $authorTag) {
			$keywords[] = $authorTag->name;
		}
		
		$workflowItems[] = [
			"name" => $independentActivity->title,
			"description" => $independentActivity->description,
			"type" => "Independent Activity",
			"subject" => $subjects,
			"educationLevel" => $educationLevels,
			"keywords" => $keywords,
			"url" => "getfromtony",
			"thumbnailUrl" => config('app.url') . $independentActivity->thumb_url,
			"media" => $this->extractMedia($independentActivity, json_decode($independentActivity->h5p_content->parameters, true), [])
		];

		return $workflowItems;
	}

	/**
     * Extract independent activity payload media
     *
     * @param IndependentActivity $independentActivity
	 * @param $h5pContentsParameters
     * @param $mediaArray
	 * 
     * @return array
     */
	public function extractMedia(IndependentActivity $independentActivity, $h5pContentsParameters, $mediaArray)
	{
		foreach ($h5pContentsParameters as $key => $value) {
			if (is_array($value) || is_object($value)) {
				// If the current value is an array or object, recursively search within it
				if ($key === "content" && isset($value['metadata'])) {
					$mediaArray[] = $this->extractMediaData($independentActivity, $value);
				} else {
					$mediaArray = $this->extractMedia($independentActivity, $value, $mediaArray);
				}
			}
		}
	
		// Value not found in this branch
		return $mediaArray;
	}

	/**
     * Extract independent activity payload media data
     *
     * @param IndependentActivity $independentActivity
	 * @param $mediaContent
     *
     * @return array
     */
	public function extractMediaData(IndependentActivity $independentActivity, $mediaContent)
	{
		$mediaData = [];

		if ($mediaContent['metadata']['contentType'] === 'Image') {
			$mediaData = [
				"identifier" => config('app.url') . '/storage/h5p/content/' . $independentActivity->h5p_content_id . '/' . $mediaContent['params']['file']['path'],
				"identifierType" => "URL",
				"name" => $mediaContent['metadata']['title'],
				"description" => $mediaContent['metadata']['licenseExtras'],
				"encodingFormat" => $mediaContent['params']['file']['mime'],
				"royalty" => [
					"type" => "usage",
					"terms" => "10.00",
					"amount" => "usage",
					"currency" => "usage",
					"copyrightNotice" => "usage",
					"creditText" => "usage"
				]
			];
		} 
		else if ($mediaContent['metadata']['contentType'] === 'Video') {
			$mediaData = [
				"identifier" => $mediaContent['params']['sources']['path'],
				"identifierType" => "URL",
				"name" => $mediaContent['metadata']['title'],
				"description" => $mediaContent['metadata']['licenseExtras'],
				"encodingFormat" => $mediaContent['params']['sources']['mime'],
				"royalty" => [
					"type" => "usage",
					"terms" => "10.00",
					"amount" => "usage",
					"currency" => "usage",
					"copyrightNotice" => "usage",
					"creditText" => "usage"
				]
			];
		}

		return $mediaData;
	}

	/**
     * Publishes a payload to the publisher
     *
     * @param Publisher $publisher
	 * @param $payload
     *
     * @return mixed
     */
	public function publishC2e(Publisher $publisher, $payload)
	{
		// Make a POST request to an external API
        $response = Http::withHeaders([
			'x-api-key' => $publisher->key,
		])->post($publisher->url . '/api/v1/c2e/publish', $payload);		

        // Check if the request was successful (status code 2xx)
        if ($response->successful()) {
            // Extract and process the response data
            $responseData = $response->json();
            // Do something with $responseData
            return $responseData;
        } else {
            // Handle the error (e.g., log it, return an error response)
            return response()->json(['error' => 'Failed to publish to store.'], $response->status());
        }
	}

	/**
     * Get publisher stores
     *
     * @param Publisher $publisher
     *
     * @return mixed
     */
	public function getStores(Publisher $publisher)
	{
		// Make a GET request to an external API
        $response = Http::withHeaders([
			'x-api-key' => $publisher->key,
		])->get($publisher->url . '/api/v1/c2e-stores');		

        // Check if the request was successful (status code 2xx)
        if ($response->successful()) {
            // Extract and process the response data
            $responseData = $response->json();
            // Do something with $responseData
            return $responseData;
        } else {
            // Handle the error (e.g., log it, return an error response)
            return response()->json(['error' => 'Failed to get stores from the publisher.'], $response->status());
        }
	}
}
