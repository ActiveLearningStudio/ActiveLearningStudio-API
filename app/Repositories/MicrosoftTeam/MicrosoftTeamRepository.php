<?php

namespace App\Repositories\MicrosoftTeam;

use App\Models\Activity;
use App\Repositories\MicrosoftTeam\MicrosoftTeamRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Http;

class MicrosoftTeamRepository extends BaseRepository implements MicrosoftTeamRepositoryInterface
{
    
    /**
    * @var string
    */
    protected $tenantId;
    
    /**
    * @var string
    */
    private $secretId;

    /**
    * @var string
    */
    private $redirectUrl;
    
    /**
    * @var string
    */
    private $loginUrl;
    
    /**
    * @var string
    */
    private $apiURL;
    /**
    * @var string
    */
    private $clientId;

    /**
    * Instantiate a MicrosoftTeam Repository instance.
    */
    public function __construct() {

        $this->tenantId = config('ms-team-configs.tenant_id');
        $this->secretId = config('ms-team-configs.secret_id');
        $this->clientId = config('ms-team-configs.client_id');
        $this->redirectUrl = config('ms-team-configs.redirect_url');
        $this->loginUrl = config('ms-team-configs.oauth_url');
        $this->apiURL = $this->loginUrl . '/' . $this->tenantId . '/oauth2/v2.0/token';
        $this->landingUrl = config('ms-team-configs.landing_url');
    }

    /**
     * @param $code string
     * @return string
     */
    public function getToken($code)
    {
        
        $postInput = [
            'grant_type' => 'authorization_code',
            'client_id' => $this->clientId,
            'client_secret' => $this->secretId,
            'redirect_uri' => $this->redirectUrl,
            'code' => $code,
            'scope' => config('ms-team-configs.scope'),
            
        ];
        
        $headers = [
            'X-header' => 'value'
        ];

        $response = Http::asForm()->withOptions(["verify"=>false])->post($this->apiURL, $postInput);

        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);
        
        return $accessToken = $responseBody['access_token'];
    }

    /**
    * @param $gid int 
    * @return string
    */
    public function getLoginUrl($gid)
    {
        return $this->loginUrl . '/' . $this->tenantId . '/oauth2/v2.0/authorize?client_id=' . 
                        $this->clientId . '&response_type=code&redirect_uri=' . $this->redirectUrl . 
                        '&response_mode=query&scope=offline_access%20user.read%20mail.read&state=' . $gid;
    }

    /**
    * @param $token string 
    * @param $data array
    *
    * return 
    */
    public function getClassesList($token)
    {
        $accountId = $this->getUserDetails($token);

        $apiURL = $this->landingUrl . 'users/' . $accountId . '/joinedTeams';
        $headers = [
            'Content-length' => 0,
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ];
  
        $response = Http::withHeaders($headers)->get($apiURL);
  
        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);
        
        return $responseBody['value'];
    }

    /**
    * @param $token string 
    * @param $data array
    *
    * @return int
    */
    public function createMsTeamClass($token, $data)
    {
        $apiURL = $this->landingUrl . 'teams';
        $data['template@odata.bind'] = $this->landingUrl . "teamsTemplates('educationClass')";
        $headers = [
            'Content-length' => 0,
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ];

        $response = Http::withHeaders($headers)->withOptions(["verify"=>false])->post($apiURL, $data);
        $statusCode = $response->status();
        \Log::info($statusCode);
        $returnArr = [
            "code" => $statusCode,
            "classId" => ($statusCode === 202) ? $this->fetchClassIdFromHeader($response->getHeaders()) : Null,
            "aSyncURL" => ($statusCode === 202) ? $response->getHeaders()['Location'][0] : Null,
        ];
        return json_encode($returnArr);
    }

   /**
    * @param $token string
    * @param $classId string
    * @param $project Project
    * @param $aSyncUrl string
    */
    public function createMSTeamAssignment($token, $classId, $project, $aSyncUrl)
    {
        \Log::info('in createMSTeamAssignment');
        if(!empty($aSyncUrl)) {
            $this->checkClassAsyncStatus($aSyncUrl, $token); // Check if class fully functional
        }
        
        $apiURL = $this->landingUrl . 'education/classes/' . $classId . '/assignments';
        $assignmentDueDays = config('ms-team-configs.assignment_due_days');

        $headers = [
            'Content-length' => 0,
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ];

        $postInput['dueDateTime'] =date('c', strtotime(date('Y-m-d'). ' + ' . $assignmentDueDays . ' days'));
        
        $playlists = $project->playlists;

        foreach ($playlists as $playlist) {
            $activities = $playlist->activities;
            foreach($activities as $activity) {

                $postInput['displayName'] = $activity->title;
                
                $response = Http::withHeaders($headers)->withOptions(["verify"=>false])
                                                ->retry(3, 6000)->post($apiURL, $postInput);
                $responseBody = json_decode($response->getBody(), true);
                
                $statusCode = $response->status();
                if ($statusCode !== 201) {
                    
                    $returnArr = [
                        "code" => $resourceStatusCode,
                        "message" => $resourceResponseBody['error']['message'],
                    ];

                    return json_encode($returnArr);
                }
                //Add link resource
                $assignmentId = $responseBody['id'];
                $resourceApiUrl = $this->landingUrl . 'education/classes/' . $classId . '/assignments/' . $assignmentId . '/resources';
                $postResourceInput = [
                    "distributeForStudentWork" => false,
                    "resource" => [
                        "displayName" => $activity->title,
                        "link" => config('constants.front-url') . '/msteams/launch/activity/' . $activity->id . '/class/' . $classId . '/assignment/' . $assignmentId,
                        "@odata.type" => "#microsoft.graph.educationLinkResource"
                    ]
                ];

                $responseResource = Http::withHeaders($headers)->withOptions(["verify"=>false])->post($resourceApiUrl, $postResourceInput);
                $resourceResponseBody = json_decode($responseResource->getBody(), true);
                $resourceStatusCode = $responseResource->status();
                
            }
        }
        $returnArr = [
            "code" => 201,
            "message" => "Projcted published successfully",
        ];

        return json_encode($returnArr);
    }

    /**
     * @param string $aSyncUrl
     * @param string $token
     */
    private function checkClassAsyncStatus($aSyncUrl, $token)
    {
        \Log::info('in checkClassAsyncStatus');
        if ($aSyncUrl !== Null) {
            $apiURL = $this->landingUrl . $aSyncUrl;
            $headers = [
                'Content-length' => 0,
                'Content-type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ];
      
            $response = Http::withHeaders($headers)->get($apiURL);
            if ($response->status() === 200) {
                $responseBody = json_decode($response->getBody(), true);
                if ($responseBody['status'] === "succeeded") {
                    return $responseBody['status'];
                } else {
                    sleep(30);
                    $this->checkClassAsyncStatus($aSyncUrl, $token);
                }
            }
        }
    }

    /**
     * @param string $header
     * @return string
     */
    private function fetchClassIdFromHeader($header)
    {
        // Can implement teamsAsyncOperation if needed
        return substr($header['Content-Location'][0], 8, 36);
    }

    /**
     * @param string $token
     */
    private function getUserDetails($token)
    {
        $apiURL = $this->landingUrl . '/me';
        $headers = [
            'Content-length' => 0,
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ];
    
        $response = Http::withHeaders($headers)->get($apiURL);
        $responseBody = json_decode($response->getBody(), true);
        return $responseBody['id'];
    }

    /**
    * @param $token string
    * @param $classId string
    * @param $Activity activity
    * @param $aSyncUrl string
    */
    public function createMSTeamIndependentActivityAssignment($token, $classId, $activity, $aSyncUrl)
    {
        \Log::info('in createMSTeamAssignment');
        if(!empty($aSyncUrl)) {
            $this->checkClassAsyncStatus($aSyncUrl, $token); // Check if class fully functional
        }
        
        $apiURL = $this->landingUrl . 'education/classes/' . $classId . '/assignments';
        $assignmentDueDays = config('ms-team-configs.assignment_due_days');

        $headers = [
            'Content-length' => 0,
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ];

        $postInput['dueDateTime'] =date('c', strtotime(date('Y-m-d'). ' + ' . $assignmentDueDays . ' days'));
        
        $postInput['displayName'] = $activity->title;
        
        $response = Http::withHeaders($headers)->withOptions(["verify"=>false])
                                        ->retry(3, 6000)->post($apiURL, $postInput);
        $responseBody = json_decode($response->getBody(), true);
        
        $statusCode = $response->status();
        if ($statusCode !== 201) {
            
            $returnArr = [
                "code" => $resourceStatusCode,
                "message" => $resourceResponseBody['error']['message'],
            ];

            return json_encode($returnArr);
        }
        //Add link resource
        $assignmentId = $responseBody['id'];
        $resourceApiUrl = $this->landingUrl . 'education/classes/' . $classId . '/assignments/' . $assignmentId . '/resources';
        $postResourceInput = [
            "distributeForStudentWork" => false,
            "resource" => [
                "displayName" => $activity->title,
                "link" => config('constants.front-url') . '/msteams/launch/activity/' . $activity->id . '/class/' . $classId . '/assignment/' . $assignmentId,
                "@odata.type" => "#microsoft.graph.educationLinkResource"
            ]
        ];

        $responseResource = Http::withHeaders($headers)->withOptions(["verify"=>false])->post($resourceApiUrl, $postResourceInput);
        $resourceResponseBody = json_decode($responseResource->getBody(), true);
        $resourceStatusCode = $responseResource->status();
                
            
        
        $returnArr = [
            "code" => 201,
            "message" => "Projcted published successfully",
        ];

        return json_encode($returnArr);
    }
    
}
