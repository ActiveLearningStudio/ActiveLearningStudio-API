<?php

namespace App\CurrikiGo\SafariMontage;

use App\CurrikiGo\SafariMontage\Client;
use App\Services\CurrikiGo\LTIConsumerService;
use App\Models\Activity as ActivityModel;
use App\Models\CurrikiGo\LmsSetting;
use App\Models\SsoLogin;
use Illuminate\Support\Facades\Auth;

/**
 * EasyUpload class for handling publishing to Safari Montage LMS
 */
class EasyUpload
{
    /**
     * LMS Settings modal object
     *
     * @var \App\Models\CurrikiGo\LmsSetting
     */
    private $lmsSetting;

    const UPLOAD_API_ENDPOINT = '/SAFARI/api/imslti.php';
    const EASY_UPLOAD_ENDPOINT = '/SAFARI/api/imsltieasyupload.php';
    const PROVIDER_NAME = 'safarimontage';
    const CONTEXT_TITLE = 'Easy Upload';

    /**
     * Make an instance of the class
     *
     * @param \App\Models\CurrikiGo\LmsSetting $lmsSetting
     */
    public function __construct(LmsSetting $lmsSetting)
    {
        $this->lmsSetting = $lmsSetting;
    }

    /**
     * Send a playlist to Safarmi Montage
     *
     * @param \App\Models\Activity $activity
     * @param array $data
     * @return array
     */
    public function uploadActivity(ActivityModel $activity, $data)
    {
        $user = Auth::user();

        // Prepare LTI request parameters.
        $key = $this->lmsSetting->lms_access_key;
        $secret = $this->lmsSetting->lms_access_secret;
        $launchUrl = $this->lmsSetting->lms_url . self::UPLOAD_API_ENDPOINT;

        $providerKey = $this->lmsSetting->lti_client_id;
        $user_id = $this->lmsSetting->lms_login_id;
        $LTI = new LTIConsumerService($key, $secret, $launchUrl);

        if (!$user_id) {
            // Get SSO login ids.
            $sso =  SsoLogin::where("provider", self::PROVIDER_NAME)->where('user_id', $user->id)->first();
            if ($sso) {
                $user_id = $sso->uniqueid;
            }
        }

        if (!$providerKey || !$user_id) {
            throw new Exception('Safari Montage Easy Upload needs provider key and user id');
        }

        $activityThumb = (strpos($activity->thumb_url, '://') === false ? config('app.url') . $activity->thumb_url : $activity->thumb_url);
        $grades = getEducationalLevel($activity->education_level_id);
        $launchData = [
            "user_id" => $user_id,
            "roles" => "Instructor,urn:lti:instrole:ims/lis/Instructor",
            "resource_link_id" => uniqid('', true),
            "resource_link_title" => $activity->title,
            "resource_link_description" => $activity->title,
            "lis_person_name_full" => $user->first_name . ' ' . $user->last_name,
            "lis_person_name_family" => $user->last_name,
            "lis_person_name_given" => $user->first_name,
            "lis_person_contact_email_primary" => $user->email,
            "lis_person_sourcedid" => $user_id,
            "context_id" => uniqid('', true),
            "context_title" => self::CONTEXT_TITLE,
            "context_label" => self::CONTEXT_TITLE,
            // custom parameters
            'custom_safarimontage_username' => $user_id,
            'custom_safarimontage_school' => config('constants.sm-publisher-name'),
            'custom_safarimontage_url' => self::EASY_UPLOAD_ENDPOINT,
            'custom_safarimontage_upload_metadata' => json_encode([
                'url' => config('constants.curriki-tsugi-host') . "?activity=" . $activity->id,
                'title' => $activity->title,
                'description' => $activity->title,
                'fromgrade' => $grades[0],
                'tograde' => (isset($grades[1]) ? $grades[1] : $grades[0]),
                'thumbnailurl' => $activityThumb,
                'mediatype' => 'web_link_interactive',
                'ltilink' => 'true',
                'ltiproviderkey' => $providerKey,
                'publisher_name' => config('constants.sm-publisher-name'),
                'publisher_icon_url' => getFrontURL() . '/' . config('constants.sm-publisher-icon'),
                'learningresourcetype' => 'interactive'
            ])
        ];

        return $LTI->launch($launchData);
    }

}
