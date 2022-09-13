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
    
        $apiURL = $this->landingUrl . 'education/classes';
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
        
        $return_arr = [
            "code" => $statusCode,
            "classId" => ($statusCode === 202) ? $this->fetchClassIdFromHeader($response->getHeaders()) : Null,
        ];
        return json_encode($return_arr);
    }

   /**
    * @param $token string
    * @param $classId string
    * @param $project Project
    */
    public function createMSTeamAssignment($token, $classId, $project)
    {
        $apiURL = $this->landingUrl . 'education/classes/' . $classId . '/assignments';
    
        $playlists = $project->playlists;

        foreach ($playlists as $playlist) {
            $activities = $playlist->activities;
            foreach($activities as $activity) {

                // Logic is in progress
                $postInput = [
                    'link' => config('constants.front-url') . '/activity/' . $activity->id . '/shared', // Need to discuss the link logic currently hardcoded
                    'displayName' => $activity->title,
                    'dueDateTime' => '2023-01-01T00:00:00Z',  // Need to discuss the due date logic currently hardcoded
                ];
                
                $headers = [
                    'Content-length' => 0,
                    'Content-type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token
                ];
            
                $response = Http::withHeaders($headers)->withOptions(["verify"=>false])->post($apiURL, $postInput);
                $responseBody = json_decode($response->getBody(), true);
                $statusCode = $response->status();
                if ($statusCode !== 201) {
                    $return_arr = [
                        "code" => $statusCode,
                        "message" => $responseBody['error']['message'] ,
                    ];

                    return json_encode($return_arr);
                }
            }
        }
        $return_arr = [
            "code" => 201,
            "message" => "Projcted published successfully",
        ];

        return json_encode($return_arr);
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
}
