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
		$contentTypes = [
			'Image',
			'Video',
			'Audio',
			'Interactive Video'
		];

		foreach ($h5pContentsParameters as $key => $value) {
			if (is_array($value) || is_object($value)) {
				// If the current value is an array or object, recursively search within it
				if (
					($key === "content" || $key === "action")
					&& (isset($value['metadata']) && in_array($value['metadata']['contentType'], $contentTypes))
				) {
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
		$identifier = '';
		$encodingFormat = '';
		$licenseExtras = '';
		$resource = '';
		$resourcePath = config('app.url') . '/storage/h5p/content/' . $independentActivity->h5p_content_id . '/';
		$royaltyType = '';
		$royaltyTermsViews = '';
		$amount = 0;
		$currency = '';
		$copyrightNotice = '';
		$creditText = '';
		$authorName = '';
		$authorEmail = '';
		$licenseUrl = '';
		$licenseType = '';
		$licenseVersion = '';
		$yearFrom = '';
		$yearTo = '';

		$licenceArray = array(
			"U" => "Undisclosed",
			"CC BY" => "Attribution (CC BY)",
			"CC BY-SA" => "Attribution-ShareAlike (CC BY-SA)",
			"CC BY-ND" => "Attribution-NoDerivs (CC BY-ND)",
			"CC BY-NC" => "Attribution-NonCommercial (CC BY-NC)",
			"CC BY-NC-SA" => "Attribution-NonCommercial-ShareAlike (CC BY-NC-SA)",
			"CC BY-NC-ND" => "Attribution-NonCommercial-NoDerivs (CC BY-NC-ND)",
			"CC0 1.0" => "Public Domain Dedication (CC0)",
			"CC PDM" => "Public Domain Mark (PDM)",
			"GNU GPL" => "General Public License v3",
			"PD" => "Public Domain",
			"ODC PDDL" => "Public Domain Dedication and Licence",
			"C" => "Copyright"
		);

		$licenceVersions = array(
			"4.0" => "4.0 International",
			"3.0" => "3.0 Unported",
			"2.5" => "2.5 Generic",
			"2.0" => "2.0 Generic",
			"1.0" => "1.0 Generic"
		);

		if ($mediaContent['metadata']['contentType'] === 'Image') {
			$mediaContentPath = $mediaContent['params']['file']['path'];
			$encodingFormat = $mediaContent['params']['file']['mime'];
		} 
		else if ($mediaContent['metadata']['contentType'] === 'Video') {
			$mediaContentPath = $mediaContent['params']['sources'][0]['path'];
			$encodingFormat = $mediaContent['params']['sources'][0]['mime'];
		}
		else if ($mediaContent['metadata']['contentType'] === 'Audio') {
			$mediaContentPath = $mediaContent['params']['files'][0]['path'];
			$encodingFormat = $mediaContent['params']['files'][0]['mime'];
		}
		else if ($mediaContent['metadata']['contentType'] === 'Interactive Video') {
			$mediaContentPath = $mediaContent['params']['interactiveVideo']['video']['files'][0]['path'];
			$encodingFormat = $mediaContent['params']['interactiveVideo']['video']['files'][0]['mime'];
		}

		if (str_contains($mediaContentPath, 'http')) {
			$resource = $mediaContentPath;
		}
		else {
			$resource = $resourcePath . $mediaContentPath;
		}

		if (isset($mediaContent['metadata']['licenseExtras'])) {
			$licenseExtras = $mediaContent['metadata']['licenseExtras'];
		}

		if (isset($mediaContent['metadata']['royaltyType'])) {
			$royaltyType = $mediaContent['metadata']['royaltyType'];
		}

		if (isset($mediaContent['metadata']['royaltyTermsViews'])) {
			$royaltyTermsViews = $mediaContent['metadata']['royaltyTermsViews'];
		}

		if (isset($mediaContent['metadata']['amount'])) {
			$amount = $mediaContent['metadata']['amount'];
		}

		if (isset($mediaContent['metadata']['currency'])) {
			$currency = $mediaContent['metadata']['currency'];
		}

		if (isset($mediaContent['metadata']['copyrightNotice'])) {
			$copyrightNotice = $mediaContent['metadata']['copyrightNotice'];
		}

		if (isset($mediaContent['metadata']['creditText'])) {
			$creditText = $mediaContent['metadata']['creditText'];
		}

		if (isset($mediaContent['metadata']['source'])) {
			$licenseUrl = $mediaContent['metadata']['source'];
		}

		if (isset($mediaContent['metadata']['license'])) {
			$licenseType = $licenceArray[$mediaContent['metadata']['license']];
		}

		if (isset($mediaContent['metadata']['licenseVersion'])) {
			$licenseVersion = $licenceVersions[$mediaContent['metadata']['licenseVersion']];
		}

		if (isset($mediaContent['metadata']['yearFrom'])) {
			$yearFrom = $mediaContent['metadata']['yearFrom'];
		}

		if (isset($mediaContent['metadata']['yearTo'])) {
			$yearTo = $mediaContent['metadata']['yearTo'];
		}

		if (isset($mediaContent['metadata']['authors'][0]['name'])) {
			$authorMetadata = $mediaContent['metadata']['authors'][0]['name'];

			if (str_contains($authorMetadata, '@')) {
				$authorData = explode(" ", $authorMetadata);
				$authorName = $authorData[0];
				$authorEmail = $authorData[count($authorData) - 1];
			}
			else {
				$authorName = $authorMetadata;
				$authorEmail = '';
			}
		}

		$mediaData = [
			"identifier" => $mediaContent['subContentId'],
			"identifierType" => "UUID",
			"name" => $mediaContent['metadata']['title'],
			"description" => $licenseExtras,
			"resource" => $resource,
			"encodingFormat" => $encodingFormat,
			"royalty" => [
				"type" => $royaltyType,
				"terms" => $royaltyTermsViews,
				"amount" => $amount,
				"currency" => $currency,
				"copyrightNotice" => $copyrightNotice,
				"license" => $creditText,
				"licenseUrl" => $licenseUrl,
				"licenseType" => $licenseType,
				"licenseVersion" => $licenseVersion,
				"yearFrom" => $yearFrom,
				"yearTo" => $yearTo
			],
			"owner" => [
				"name" => $authorName,
				"email" => $authorEmail
			]
		];

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
