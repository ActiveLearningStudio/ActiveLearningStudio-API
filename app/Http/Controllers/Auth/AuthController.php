<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\GoogleLoginRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\SsoLoginRequest;
use App\Http\Requests\V1\WordpressSSOLoginRequest;
use App\Http\Resources\V1\UserResource;
use App\Http\Resources\V1\DefaultSsoSettingsResource;
use App\Repositories\DefaultSsoIntegrationSettings\DefaultSsoIntegrationSettingsRepository;
use App\Repositories\Group\GroupRepositoryInterface;
use App\Repositories\InvitedGroupUser\InvitedGroupUserRepositoryInterface;
use App\Repositories\InvitedOrganizationUser\InvitedOrganizationUserRepositoryInterface;
use App\Repositories\InvitedTeamUser\InvitedTeamUserRepositoryInterface;
use App\Repositories\Organization\OrganizationRepositoryInterface;
use App\Repositories\Team\TeamRepositoryInterface;
use App\Repositories\UserLogin\UserLoginRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
/**
 * @group 1. Authentication
 *
 * APIs for Authentication
 */
class AuthController extends Controller
{
    private $userRepository;
    private $userLoginRepository;
    private $invitedTeamUserRepository;
    private $invitedOrganizationUserRepository;
    private $invitedGroupUserRepository;
    private $teamRepository;
    private $groupRepository;
    private $organizationRepository;
    private $defaultSsoSettingsRepository;

    /**
     * AuthController constructor.
     *
     * @param UserRepositoryInterface $userRepository
     * @param UserLoginRepositoryInterface $userLoginRepository
     * @param InvitedTeamUserRepositoryInterface $invitedTeamUserRepository
     * @param InvitedOrganizationUserRepositoryInterface $invitedOrganizationUserRepository
     * @param InvitedGroupUserRepositoryInterface $invitedGroupUserRepository
     * @param TeamRepositoryInterface $teamRepository
     * @param GroupRepositoryInterface $groupRepository
     * @param OrganizationRepositoryInterface $organizationRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        UserLoginRepositoryInterface $userLoginRepository,
        InvitedTeamUserRepositoryInterface $invitedTeamUserRepository,
        InvitedOrganizationUserRepositoryInterface $invitedOrganizationUserRepository,
        InvitedGroupUserRepositoryInterface $invitedGroupUserRepository,
        TeamRepositoryInterface $teamRepository,
        GroupRepositoryInterface $groupRepository,
        OrganizationRepositoryInterface $organizationRepository,
        DefaultSsoIntegrationSettingsRepository $defaultSsoSettingsRepository
    ) {
        $this->userRepository = $userRepository;
        $this->userLoginRepository = $userLoginRepository;
        $this->invitedTeamUserRepository = $invitedTeamUserRepository;
        $this->invitedOrganizationUserRepository = $invitedOrganizationUserRepository;
        $this->invitedGroupUserRepository = $invitedGroupUserRepository;
        $this->teamRepository = $teamRepository;
        $this->groupRepository = $groupRepository;
        $this->organizationRepository = $organizationRepository;
        $this->defaultSsoSettingsRepository = $defaultSsoSettingsRepository;
    }

    /**
     * Register
     *
     * @bodyParam first_name string required First name of a user Example: John
     * @bodyParam last_name string required Last name of a user Example: Doe
     * @bodyParam email string required Email of a user Example: john.doe@currikistudio.org
     * @bodyParam password string required Password Example: Password123
     * @bodyParam organization_name string required Organization name of a user Example: Curriki
     * @bodyParam organization_type string required Organization type of a user Example: Nonprofit
     * @bodyParam job_title string required Job title of a user Example: Developer
     * @bodyParam domain string required Organization domain user is registering for Example: currikistudio
     *
     * @response 201 {
     *   "message": "You are one step away from building the world's most immersive learning experiences with CurrikiStudio!"
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Could not create user account. Please try again later."
     *   ]
     * }
     *
     * @param RegisterRequest $registerRequest
     * @return Response
     */
    public function register(RegisterRequest $registerRequest)
    {
        $data = $registerRequest->validated();

        $invited_users = $this->invitedOrganizationUserRepository->searchByEmail($data['email']);

        if ($invited_users->isEmpty()) {
            $organization = $this->organizationRepository->findByField('domain', $data['domain']);
            if ($organization && !$organization->self_registration) {
                return response()->error(['Self registration is not allowed on this domain.', 400]);
            }
        }

        $data['password'] = Hash::make($data['password']);
        $data['remember_token'] = Str::random(64);
        $data['email_verified_at'] = now();

        $user = $this->userRepository->create(Arr::except($data, ['domain']));

        if ($user) {
            // $invited_users = $this->invitedTeamUserRepository->searchByEmail($data['email']);
            // if ($invited_users) {
            //     foreach ($invited_users as $invited_user) {
            //         $team = $this->teamRepository->find($invited_user->team_id);
            //         if ($team) {
            //             $team->users()->attach($user, ['role' => 'collaborator', 'token' => $invited_user->token]);
            //             $this->invitedTeamUserRepository->delete($data['email']);
            //         }
            //     }
            // }

            event(new Registered($user));

            $invited_users = $this->invitedOrganizationUserRepository->searchByEmail($data['email']);
            if ($invited_users->isNotEmpty()) {
                foreach ($invited_users as $invited_user) {
                    $organization = $this->organizationRepository->find($invited_user->organization_id);
                    if ($organization) {
                        $exist_user_id = $organization->users()->where('user_id', $user->id)->first();
                        if (!$exist_user_id) {
                            $organization->users()->attach($user, ['organization_role_type_id' => $invited_user->organization_role_type_id]);
                        }
                        $this->invitedOrganizationUserRepository->delete($data['email']);
                    }
                }
            } else {
                $organization = $this->organizationRepository->findByField('domain', $data['domain']);
                if ($organization) {
                    $exist_user_id = $organization->users()->where('user_id', $user->id)->first();
                    if (!$exist_user_id) {
                        $selfRegisteredRole = $organization->roles()->where('name', 'self_registered')->first();
                        if (!$selfRegisteredRole) {
                            $selfRegisteredRole = $this->organizationRepository->duplicateRole($organization, 'self_registered');
                        }
                        $organization->users()->attach($user, ['organization_role_type_id' => $selfRegisteredRole->id]);
                    }
                }
            }

            $invited_users = $this->invitedGroupUserRepository->searchByEmail($data['email']);
            if ($invited_users->isNotEmpty()) {
                foreach ($invited_users as $invited_user) {
                    $group = $this->groupRepository->find($invited_user->group_id);
                    if ($group) {
                        $group->users()->attach($user, ['role' => 'collaborator', 'token' => $invited_user->token]);
                        $this->invitedGroupUserRepository->delete($data['email']);
                    }
                }
            }

            return response([
                'message' => "You are one step away from building the world's most immersive learning experiences with CurrikiStudio!",
            ], 201);
        }

        return response([
            'errors' => ['Could not create user account. Please try again later.'],
        ], 500);
    }

    /**
     * Login
     *
     * @bodyParam email string required The email of a user Example: john.doe@currikistudio.org
     * @bodyParam password string required The password corresponded to the email Example: Password123
     * @bodyParam domain string required Organization domain to get data for Example: curriki
     *
     * @responseFile responses/user/user-with-token.json
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid Credentials."
     *   ]
     * }
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid Domain."
     *   ]
     * }
     *
     * @response 400 {
     *   "errors": [
     *     "Email is not verified."
     *   ]
     * }
     *
     * @param LoginRequest $loginRequest
     * @return Response
     */
    public function login(LoginRequest $loginRequest)
    {
        $data = $loginRequest->validated();
        $domain = isset($data['domain']) ? $data['domain'] : null;
        unset($data['domain']);

        if (!auth()->attempt($data)) {
            return response([
                'errors' => ['Invalid Credentials.'],
            ], 400);
        }

        $user = auth()->user();

        if (!$organization = $user->organizations()->where('domain', $domain)->first()) {
            return response([
                'errors' => ['Invalid Domain.'],
            ], 400);
        }

        if (!$user->email_verified_at) {
            return response([
                'errors' => ['Email is not verified.'],
            ], 400);
        }

        $accessToken = $user->createToken('auth_token')->accessToken;

        $this->userLoginRepository->create([
            'user_id' => $user->id,
            'ip_address' => $loginRequest->ip(),
        ]);

        return response([
            'user' => new UserResource($user),
            'access_token' => $accessToken,
        ], 200);
    }

    /**
     * Login with Google
     *
     * @bodyParam tokenId string required The token Id of google login Example: eyJhbGciOiJSUzI1NiIsImtpZCI6IjJjNmZh...
     * @bodyParam tokenObj object required The token object of google login
     * @bodyParam tokenObj.token_type string required The token type of google login Example: Bearer
     * @bodyParam tokenObj.access_token string required The access token of google login Example: ya29.a0AfH6SMBx-CIZfKRorxn8xPugO...
     * @bodyParam tokenObj.scope string required The token scope of google login Example: email profile ...
     * @bodyParam tokenObj.login_hint string required The token hint of google login Example: AJDLj6JUa8yxXrhHdWRHIV0...
     * @bodyParam tokenObj.expires_in int required The token expire of google login Example: 3599
     * @bodyParam tokenObj.id_token string required The token Id of google login Example: eyJhbGciOiJSUzI1NiIsImtpZCI6I...
     * @bodyParam tokenObj.session_state object required The session state of google login
     * @bodyParam tokenObj.session_state.extraQueryParams object required
     * @bodyParam tokenObj.session_state.extraQueryParams.authuser string required Example: 0
     * @bodyParam tokenObj.first_issued_at int required The first issued time of google login Example: 1601535932504
     * @bodyParam tokenObj.expires_at int required The expire time of google login Example: 1601539531504
     * @bodyParam tokenObj.idpId string required The idp Id of google login Example: google
     *
     * @responseFile responses/user/user-with-token.json
     *
     * @response 400 {
     *   "errors": [
     *     "Unable to login with Google."
     *   ]
     * }
     *
     * @param Request $request
     * @return Response
     */
    public function loginWithGoogle(GoogleLoginRequest $request)
    {
        $client = new \Google_Client();
        $client->setClientId(config('google.gapi_client_id'));
        $result = $client->verifyIdToken($request->tokenId);

        if ($result) {
            $user = $this->userRepository->findByField('email', $result['email']);
            if (!$user) {
                $invited_users = $this->invitedOrganizationUserRepository->searchByEmail($result['email']);

                if ($invited_users->isEmpty()) {
                    $organization = $this->organizationRepository->findByField('domain', $request->domain);
                    if ($organization && !$organization->self_registration) {
                        return response()->error(['Self registration is not allowed on this domain.', 400]);
                    }
                }

                $password = Str::random(10);
                $userData = [
                    'first_name' => $result['name'],
                    'last_name' => ' ',
                    'email' => $result['email'],
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(64),
                    'email_verified_at' => now(),
                ];

                if (isset($request->organization_name)) {
                    $userData['organization_name'] = $request->organization_name;
                }

                if (isset($request->organization_type)) {
                    $userData['organization_type'] = $request->organization_type;
                }

                if (isset($request->job_title)) {
                    $userData['job_title'] = $request->job_title;
                }

                $user = $this->userRepository->create($userData);

                if ($user) {
                    // $invited_users = $this->invitedTeamUserRepository->searchByEmail($user->email);
                    // if ($invited_users) {
                    //     foreach ($invited_users as $invited_user) {
                    //         $team = $this->teamRepository->find($invited_user->team_id);
                    //         if ($team) {
                    //             $team->users()->attach($user, ['role' => 'collaborator', 'token' => $invited_user->token]);
                    //             $this->invitedTeamUserRepository->delete($user->email);
                    //         }
                    //     }
                    // }

                    $invited_users = $this->invitedOrganizationUserRepository->searchByEmail($user->email);
                    if ($invited_users->isNotEmpty()) {
                        foreach ($invited_users as $invited_user) {
                            $organization = $this->organizationRepository->find($invited_user->organization_id);
                            if ($organization) {
                                $organization->users()->attach($user, ['organization_role_type_id' => $invited_user->organization_role_type_id]);
                                $this->invitedOrganizationUserRepository->delete($user->email);
                            }
                        }
                    } else {
                        $organization = $this->organizationRepository->findByField('domain', $request->domain);
                        if ($organization) {
                            $selfRegisteredRole = $organization->roles()->where('name', 'self_registered')->first();
                            if (!$selfRegisteredRole) {
                                $selfRegisteredRole = $this->organizationRepository->duplicateRole($organization, 'self_registered');
                            }
                            $organization->users()->attach($user, ['organization_role_type_id' => $selfRegisteredRole->id]);
                        }
                    }

                    $invited_users = $this->invitedGroupUserRepository->searchByEmail($user->email);
                    if ($invited_users->isNotEmpty()) {
                        foreach ($invited_users as $invited_user) {
                            $group = $this->groupRepository->find($invited_user->group_id);
                            if ($group) {
                                $group->users()->attach($user, ['role' => 'collaborator', 'token' => $invited_user->token]);
                                $this->invitedGroupUserRepository->delete($user->email);
                            }
                        }
                    }
                }
            } else {
                if (isset($request->organization_name) || isset($request->organization_type) || isset($request->job_title)) {
                    return response()->error(['Email already registered.', 400]);
                }

                $domainOrganization = $this->organizationRepository->findByField('domain', $request->domain);
                $organization = $user->organizations()->where('domain', $request->domain)->first();

                if (!$domainOrganization) {
                    return response([
                        'errors' => ['Invalid Domain.'],
                    ], 400);
                } else if (!$organization && !$domainOrganization->self_registration) {
                    return response()->error(['Self registration is not allowed on this domain.', 400]);
                } else if (!$organization && $domainOrganization->self_registration) {
                    $selfRegisteredRole = $domainOrganization->roles()->where('name', 'self_registered')->first();
                    if (!$selfRegisteredRole) {
                        $selfRegisteredRole = $this->organizationRepository->duplicateRole($domainOrganization, 'self_registered');
                    }
                    $domainOrganization->users()->attach($user, ['organization_role_type_id' => $selfRegisteredRole->id]);
                }
            }
            $user->gapi_access_token = $request->tokenObj;
            $user->save();

            $accessToken = $user->createToken('auth_token')->accessToken;

            $this->userLoginRepository->create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
            ]);

            return response([
                'user' => new UserResource($user),
                'access_token' => $accessToken,
            ], 200);
        }

        return response([
            'errors' => ['Unable to login with Google.'],
        ], 400);
    }

    /**
     * Login with SSO
     *
     * @bodyParam sso_info string required The base64encode query params Example: dXNlcl9rZXk9YWFobWFkJnVzZXJfZW1haWw9YXFlZWwuYWhtYWQlNDB...
     *
     * @responseFile responses/user/user-with-token.json
     *
     * @response 400 {
     *   "errors": [
     *     "Unable to login with SSO."
     *   ]
     * }
     *
     * @param SsoLoginRequest $request
     * @return Response
     */
    public function ssoLogin(SsoLoginRequest $request)
    {
        try {
            $data = $request->validated();
            parse_str(base64_decode($data['sso_info']), $result);
            if ($result) {
                $response = $this->createUpdateSsoUser($request->ip(), $result, null);
                return response($response, 200);
            }
            return response([
                'errors' => ['Unable to login with SSO.'],
            ], 400);
        } catch (\Exception $e) {
            \Log::error($e->getLine(). "/" .$e->getMessage());
            return response([
                'errors' => ['Unable to login with SSO.'],
            ], 400);
        }
    }

    /**
     * Oaut Redirect
     *
     * @responseFile responses/user/user-with-token.json
     *
     * @response 404 - Redirect
     *
     * @param oauthRedirect $request
     * @return Redirect
     */
    public function oauthRedirect(Request $request)
    {
        try {
            return redirect()->away(config("services.stemuli.basic_url") . '?response_type=' . config("services.stemuli.response_type") . '&client_id=' . config("services.stemuli.client") . '&redirect_uri=' . config("services.stemuli.redirect_uri") . '&scope=' . config("services.stemuli.scope"));
        } catch (\Exception $e) {
            \Log::error($e->getLine() ."/". $e->getMessage());
            return redirect()->back()->with('errors', 'Unable to redirect. Please try again later.');;
        }
    }
    /**
     * Oaut oauthCallBack
     *
     * @responseFile responses/user/user-with-token.json
     *
     * @response 404 - Redirect back
     *
     * @param oauthCallBack $request
     * @return Redirect
     */
    public function oauthCallBack(Request $request)
    {
        try {
            $data = array(
                "grant_type" => "authorization_code",
                "client_id" => config('services.stemuli.client'),
                "redirect_uri" => config('services.stemuli.redirect_uri'),
                "code" => $request->code,
                "client_secret"=> config('services.stemuli.secret')
            );
            $client = new Client();
            $url = config('services.stemuli.token_url');
            $curl_request = $client->post($url,  array(
                'form_params' => $data,
                'Content-Type' => 'application/json',
            ));
            $response = json_decode($curl_request->getBody(), true);
            if ($curl_request->getStatusCode() === 200 && !isset($response['error'])) {
                $response = $response;
                $response['info']['client_id'] = config('services.stemuli.client');
                return $this->stemuliSsoLogin($request->ip(), $response['info']);
            } else {
                $response = $response['error'];
                \Log::error($response);
                return redirect()->back()->with('errors', $response);
            }
        } catch (\Exception $e) {
            \Log::error($e->getLine() ."/". $e->getMessage());
            return response([
                'errors' => ['Unable to login with SSO.' . $e->getMessage()],
            ], 400);
        }
    }

    private function stemuliSsoLogin($ip, $info)
    {
        try {
            if ($info) {
                $user = User::with(['lmssetting' => function($query) use ($info) {
                    $query->where('lti_client_id', $info['client_id']);
                }])->where('email', $info['email'])->first();

                if (!$user) {
                    $password = Str::random(10);
                    $user = $this->userRepository->create([
                        'first_name' => $info['first_name'],
                        'last_name' => $info['last_name'],
                        'email' => $info['email'],
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(64),
                        'email_verified_at' => now(),
                    ]);
                    if ($user) {
                        $default_lms_setting = $this->defaultSsoSettingsRepository->findByField('lti_client_id', $info['client_id']);
                        //if default LMS setting not exist!
                        if (!$default_lms_setting) {
                            return response([
                                'errors' => ['Unable to find default LMS setting with your client id.'],
                            ], 404);
                        }
                        $default_lms_setting = $default_lms_setting->toArray();
                        $default_lms_setting['lms_login_id'] = $user['email'];
                        $user->lmssetting()->create($default_lms_setting);

                        $user->ssoLogin()->create([
                            'user_id' => $user->id,
                            'provider' => 'stemuli',
                            'uniqueid' => $info['email'],
                            'tool_consumer_instance_name' => '',
                            'tool_consumer_instance_guid' => '',
                            'custom_school' => '',
                        ]);
                        $user['user_organization'] = $user->lmssetting[0]->organization;

                        $organization = $this->organizationRepository->find($user['user_organization']['id']);
                        if ($organization) {
                            if($default_lms_setting['role_id']) {
                                $organization->users()->attach($user, ['organization_role_type_id' => $default_lms_setting['role_id']]);
                            } else {
                                $selfRegisteredRole = $organization->roles()->where('name', 'self_registered')->first();
                                $organization->users()->attach($user, ['organization_role_type_id' => $selfRegisteredRole->id]);
                            }
                        }
                    }
                } else {
                    if (sizeof($user->lmssetting) > 0) {
                        $user['user_organization'] = $user->lmssetting[0]->organization;
                    } else {
                        $default_lms_setting = $this->defaultSsoSettingsRepository->findByField('lti_client_id', $info['client_id']);
                        //if default LMS setting not exist!
                        if (!$default_lms_setting) {
                            return response([
                                'errors' => ['Unable to find default LMS setting with your client id.'],
                            ], 404);
                        }
                        $default_lms_setting = $default_lms_setting->toArray();
                        $default_lms_setting['lms_login_id'] = $user['email'];
                        $newly_created_setting = $user->lmssetting()->create($default_lms_setting);

                        $organization = $this->organizationRepository->find($newly_created_setting->id);
                        if ($organization) {
                            if($default_lms_setting['role_id']) {
                                $organization->users()->attach($user, ['organization_role_type_id' => $default_lms_setting['role_id']]);
                            } else {
                                $selfRegisteredRole = $organization->roles()->where('name', 'self_registered')->first();
                                $organization->users()->attach($user, ['organization_role_type_id' => $selfRegisteredRole->id]);
                            }
                        }
                    }

                    $sso_login = $user->ssoLogin()->where([
                        'user_id' => $user->id,
                        'provider' => 'stemuli',
                        'tool_consumer_instance_guid' => ''
                    ])->first();

                    if (!$sso_login) {
                        $user->ssoLogin()->create([
                            'user_id' => $user->id,
                            'provider' => 'stemuli',
                            'uniqueid' => $info['email'],
                            'tool_consumer_instance_name' => '',
                            'tool_consumer_instance_guid' => '',
                            'custom_school' => '',
                        ]);
                    }
                }

                $accessToken = $user->createToken('auth_token')->accessToken;

                $this->userLoginRepository->create(['user_id' => $user->id, 'ip_address' => $ip]);

                $data['user'] = $user->toArray();
                $data['access_token'] = $accessToken;
                $build_request_data = json_encode($data);
                $user_info = base64_encode($build_request_data);
                return redirect()->away(config('app.front_end_url') . '/sso/dologin/' . $user_info);
            }
            return response([
                'errors' => ['Unable to login with SSO. Info is empty'],
            ], 400);
        } catch (\Exception $e) {
            \Log::error($e->getLine() . "/" . $e->getMessage());
            return response([
                'errors' => ['Unable to login with SSO.'],
            ], 400);
        }
    }

    private function createUpdateSsoUser($ip, $result, $provider)
    {
        $user = $this->userRepository->findByField('email', $result['user_email']);
        if (!$user) {
            $password = Str::random(10);
            $user = $this->userRepository->create([
                'first_name' => $result['first_name'],
                'last_name' => $result['last_name'],
                'email' => $result['user_email'],
                'password' => Hash::make($password),
                'remember_token' => Str::random(64),
                'email_verified_at' => now(),
            ]);
            if ($user) {
                $user->ssoLogin()->create([
                    'user_id' => $user->id,
                    'provider' => $result['tool_platform'],
                    'uniqueid' => $result['user_key'],
                    'tool_consumer_instance_name' => $result['tool_consumer_instance_name'],
                    'tool_consumer_instance_guid' => $result['tool_consumer_instance_guid'],
                    'custom_school' => ($result['tool_platform']) ? $result['custom_' . $result['tool_platform'] . '_school'] : 'Curriki School',
                ]);
                // $invited_users = $this->invitedTeamUserRepository->searchByEmail($user->email);
                // if ($invited_users) {
                //     foreach ($invited_users as $invited_user) {
                //         $team = $this->teamRepository->find($invited_user->team_id);
                //         if ($team) {
                //             $team->users()->attach($user, ['role' => 'collaborator', 'token' => $invited_user->token]);
                //             $this->invitedTeamUserRepository->delete($user->email);
                //         }
                //     }
                // }

                $invited_users = $this->invitedOrganizationUserRepository->searchByEmail($user->email);
                if ($invited_users->isNotEmpty()) {
                    foreach ($invited_users as $invited_user) {
                        $organization = $this->organizationRepository->find($invited_user->organization_id);
                        if ($organization) {
                            $organization->users()->attach($user, ['organization_role_type_id' => $invited_user->organization_role_type_id]);
                            $this->invitedOrganizationUserRepository->delete($user->email);
                        }
                    }
                } else {
                    $organization = $this->organizationRepository->find(1);
                    if ($organization) {
                        $selfRegisteredRole = $organization->roles()->where('name', 'self_registered')->first();
                        $organization->users()->attach($user, ['organization_role_type_id' => $selfRegisteredRole->id]);
                    }
                }

                $invited_users = $this->invitedGroupUserRepository->searchByEmail($user->email);
                if ($invited_users->isNotEmpty()) {
                    foreach ($invited_users as $invited_user) {
                        $group = $this->groupRepository->find($invited_user->group_id);
                        if ($group) {
                            $group->users()->attach($user, ['role' => 'collaborator', 'token' => $invited_user->token]);
                            $this->invitedGroupUserRepository->delete($user->email);
                        }
                    }
                }
            }
        } else {
            $sso_login = $user->ssoLogin()->where([
                'user_id' => $user->id,
                'provider' => $result['tool_platform'],
                'tool_consumer_instance_guid' => $result['tool_consumer_instance_guid'
                ]])->first();
            if (!$sso_login) {
                $user->ssoLogin()->create([
                    'user_id' => $user->id,
                    'provider' => $result['tool_platform'],
                    'uniqueid' => $result['user_key'],
                    'tool_consumer_instance_name' => $result['tool_consumer_instance_name'],
                    'tool_consumer_instance_guid' => $result['tool_consumer_instance_guid'],
                    'custom_school' => ($result['tool_platform']) ? $result['custom_' . $result['tool_platform'] . '_school'] : 'Curriki School',
                ]);
            }
        }

        $accessToken = $user->createToken('auth_token')->accessToken;

        $this->userLoginRepository->create([
            'user_id' => $user->id,
            'ip_address' => $ip,
        ]);
        $response = [
            'user' => new UserResource($user),
            'access_token' => $accessToken,
        ];
        if ($provider === 'stemuli') {
            $data['user'] = $user->toArray();
            $data['access_token'] = $accessToken;
            $build_request_data = json_encode($data);
            $user_info = base64_encode($build_request_data);
            return redirect()->away(config('app.front_end_url').'/sso/dologin/'.$user_info);
        } else {
            return $response;
        }
    }

    /**
     * Login with LTI SSO
     *
     * @bodyParam sso_info string required The base64encode query params Example: dXNlcl9rZXk9YWFobWFkJnVzZXJfZW1haWw9YXFlZWwuYWhtYWQlNDB...
     *
     * @responseFile responses/user/user-with-token.json
     *
     * @response 400 {
     *   "errors": [
     *     "Unable to login with LTI SSO."
     *   ]
     * }
     *
     * @param SsoLoginRequest $request
     * @return Response
     */
    public function ltiSsoLogin(SsoLoginRequest $request)
    {
        $data = $request->validated();
        parse_str(base64_decode($data['sso_info']), $result);

        $user = User::with(['lmssetting' => function($query) use ($result) {
            $query->where('lti_client_id', $result['lti_client_id']);
        }])->where('email', $result['email'])->first();

        if (!$user) {
            $password = Str::random(10);
            $user = $this->userRepository->create([
                'first_name' => $result['first_name'],
                'last_name' => $result['last_name'],
                'email' => $result['email'],
                'password' => Hash::make($password),
                'remember_token' => Str::random(64),
                'email_verified_at' => now(),
            ]);
            if ($user) {
                $default_lms_setting = $this->defaultSsoSettingsRepository->findByField('lti_client_id', $result['lti_client_id']);
                //if default LMS setting not exist!
                if (!$default_lms_setting) {
                    return response([
                        'errors' => ['Unable to find default LMS setting with your client id.'],
                    ], 404);
                }
                $default_lms_setting = $default_lms_setting->toArray();
                $default_lms_setting['lms_login_id'] = $user['email'];
                $user->lmssetting()->create($default_lms_setting);

                $user->ssoLogin()->create([
                    'user_id' => $user->id,
                    'provider' => $result['tool_platform'],
                    'uniqueid' => $user->id . '-' . $result['guid'],
                    //'tool_consumer_instance_name' => $result['tool_consumer_instance_name'],
                    'tool_consumer_instance_guid' => $result['guid'],
                    //'custom_school' => ($result['tool_platform']) ? $result['custom_' . $result['tool_platform'] . '_school'] : 'Curriki School',
                ]);
                $user['user_organization'] = $user->lmssetting[0]->organization;

                $organization = $this->organizationRepository->find($user['user_organization']['id']);
                if ($organization) {
                    if($default_lms_setting['role_id']) {
                        $organization->users()->attach($user, ['organization_role_type_id' => $default_lms_setting['role_id']]);
                    }
                    else {
                        $selfRegisteredRole = $organization->roles()->where('name', 'self_registered')->first();
                        $organization->users()->attach($user, ['organization_role_type_id' => $selfRegisteredRole->id]);
                    }
                }
            }
        } else {
            if (sizeof($user->lmssetting) > 0) {
                $user['user_organization'] = $user->lmssetting[0]->organization;
            } else {
                $default_lms_setting = $this->defaultSsoSettingsRepository->findByField('lti_client_id', $result['lti_client_id']);
                //if default LMS setting not exist!
                if (!$default_lms_setting) {
                    return response([
                        'errors' => ['Unable to find default LMS setting with your client id.'],
                    ], 404);
                }
                $default_lms_setting = $default_lms_setting->toArray();
                $default_lms_setting['lms_login_id'] = $user['email'];
                $newly_created_setting = $user->lmssetting()->create($default_lms_setting);

                $organization = $this->organizationRepository->find($newly_created_setting->id);
                if ($organization) {
                    if($default_lms_setting['role_id']) {
                        $organization->users()->attach($user, ['organization_role_type_id' => $default_lms_setting['role_id']]);
                    }
                    else {
                        $selfRegisteredRole = $organization->roles()->where('name', 'self_registered')->first();
                        $organization->users()->attach($user, ['organization_role_type_id' => $selfRegisteredRole->id]);
                    }
                }
            }

            $sso_login = $user->ssoLogin()->where([
                'user_id' => $user->id,
                'provider' => $result['tool_platform'],
                'tool_consumer_instance_guid' => $result['guid']
            ])->first();

            if (!$sso_login) {
                $user->ssoLogin()->create([
                    'user_id' => $user->id,
                    'provider' => $result['tool_platform'],
                    'uniqueid' => $user->id . '-' . $result['guid'],
                    //'tool_consumer_instance_name' => $result['tool_consumer_instance_name'],
                    'tool_consumer_instance_guid' => $result['guid'],
                    //'custom_school' => ($result['tool_platform']) ? $result['custom_' . $result['tool_platform'] . '_school'] : 'Curriki School',
                ]);
            }
        }

        $accessToken = $user->createToken('auth_token')->accessToken;

        $this->userLoginRepository->create(['user_id' => $user->id, 'ip_address' => $request->ip()]);

        $response = ['user' => $user, 'access_token' => $accessToken];

        return $response;
    }



    /**
     * Login with LTI SSO 1.0
     *
     * @bodyParam sso_info string required The base64encode query params Example: dXNlcl9rZXk9YWFobWFkJnVzZXJfZW1haWw9YXFlZWwuYWhtYWQlNDB...
     *
     * @responseFile responses/user/user-with-token.json
     *
     * @response 400 {
     *   "errors": [
     *     "Unable to login with LTI SSO."
     *   ]
     * }
     *
     * @param SsoLoginRequest $request
     * @return Response
     */
    public function ltiSsoLogin1p0(SsoLoginRequest $request) {
        $data = $request->validated();
        parse_str(base64_decode($data['sso_info']), $result);

        $user = User::with(['lmssetting' => function($query) use ($result) {
            $query->where('lms_access_key', $result['oauth_consumer_key']);
        }])->where('email', $result['user_email'])->first();

        if (!$user) {
            $password = Str::random(10);
            $user = $this->userRepository->create([
                'first_name' => $result['first_name'],
                'last_name' => $result['last_name'],
                'email' => $result['user_email'],
                'password' => Hash::make($password),
                'remember_token' => Str::random(64),
                'email_verified_at' => now(),
            ]);
            if ($user) {
                $default_lms_setting = $this->defaultSsoSettingsRepository->findByField('lms_access_key', $result['oauth_consumer_key']);
                //if default LMS setting not exist!
                if (!$default_lms_setting) {
                    return response([
                        'errors' => ['Unable to find default LMS setting with your client id.'],
                    ], 404);
                }

                $default_lms_setting = $default_lms_setting->toArray();
                $default_lms_setting['lms_login_id'] = $user['user_email'];
                $user->lmssetting()->create($default_lms_setting);

                $user->ssoLogin()->create([
                    'user_id' => $user->id,
                    'provider' => $result['tool_platform'],
                    'uniqueid' => $result['user_key'],
                    'tool_consumer_instance_name' => $result['tool_consumer_instance_name'],
                    'tool_consumer_instance_guid' => $result['tool_consumer_instance_guid'],
                    'custom_school' => ($result['tool_platform']) ? $result['custom_' . $result['tool_platform'] . '_school'] : 'Curriki School',
                ]);

                $user['user_organization'] = $user->lmssetting[0]->organization;

                $organization = $this->organizationRepository->find($user['user_organization']['id']);
                if ($organization) {
                    if ($default_lms_setting['role_id']) {
                        $organization->users()->attach($user, ['organization_role_type_id' => $default_lms_setting['role_id']]);
                    } else {
                        $selfRegisteredRole = $organization->roles()->where('name', 'self_registered')->first();
                        $organization->users()->attach($user, ['organization_role_type_id' => $selfRegisteredRole->id]);
                    }
                }
            }
        } else {
            if (sizeof($user->lmssetting) > 0) {
                $user['user_organization'] = $user->lmssetting[0]->organization;
            } else {
                $default_lms_setting = $this->defaultSsoSettingsRepository->findByField('lms_access_key', $result['oauth_consumer_key']);
                //if default LMS setting not exist!
                if (!$default_lms_setting) {
                    return response([
                        'errors' => ['Unable to find default LMS setting with your client id.'],
                    ], 404);
                }
                $default_lms_setting = $default_lms_setting->toArray();
                $default_lms_setting['lms_login_id'] = $user['user_email'];
                $newly_created_setting = $user->lmssetting()->create($default_lms_setting);

                $organization = $this->organizationRepository->find($newly_created_setting->id);
                if ($organization) {
                    if ($default_lms_setting['role_id']) {
                        $organization->users()->attach($user, ['organization_role_type_id' => $default_lms_setting['role_id']]);
                    } else {
                        $selfRegisteredRole = $organization->roles()->where('name', 'self_registered')->first();
                        $organization->users()->attach($user, ['organization_role_type_id' => $selfRegisteredRole->id]);
                    }
                }
            }
        }
        $sso_login = $user->ssoLogin()->where([
            'user_id' => $user->id,
            'provider' => $result['tool_platform'],
            'tool_consumer_instance_guid' => $result['tool_consumer_instance_guid']
        ])->first();

        if (!$sso_login) {
            $user->ssoLogin()->create([
                'user_id' => $user->id,
                'provider' => $result['tool_platform'],
                'uniqueid' => $result['user_key'],
                'tool_consumer_instance_name' => $result['tool_consumer_instance_name'],
                'tool_consumer_instance_guid' => $result['tool_consumer_instance_guid'],
                'custom_school' => ($result['tool_platform']) ? $result['custom_' . $result['tool_platform'] . '_school'] : 'Curriki School',
            ]);
        }
        $accessToken = $user->createToken('auth_token')->accessToken;
        $this->userLoginRepository->create(['user_id' => $user->id, 'ip_address' => $request->ip()]);
        $response = ['user' => $user, 'access_token' => $accessToken];
        return $response;
    }

    /**
     * Logout
     *
     * @response {
     *   "message": "You have been successfully logged out."
     * }
     *
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        return response([
            'message' => 'You have been successfully logged out.',
        ], 200);
    }

    /**
     * Admin Login
     *
     * @bodyParam email string required The email of a user Example: john.doe@currikistudio.org
     * @bodyParam password string required The password corresponded to the email Example: Password123
     *
     * @responseFile responses/user/user-with-token.json
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid Credentials."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Email is not verified."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Unauthorized!"
     *   ]
     * }
     *
     * @param LoginRequest $loginRequest
     * @return Application|ResponseFactory|Response
     * @throws \Throwable
     */
    public function adminLogin(LoginRequest $loginRequest)
    {
        $data = $loginRequest->validated();

        if (!auth()->attempt($data)) {
            return response([
                'errors' => ['Invalid Credentials.'],
            ], 400);
        }

        $user = auth()->user();

        throw_if(!$user->email_verified_at, new GeneralException('Email is not verified!')); // make sure admin email is verified
        throw_if(!$user->isAdmin(), new GeneralException('Unauthorized!')); // if not admin then throw unauthorized error

        // keep the login logs
        $this->userLoginRepository->create([
            'user_id' => $user->id,
            'ip_address' => $loginRequest->ip(),
        ]);

        return response([
            'user' => new UserResource($user),
            'access_token' => $user->createToken('auth_token')->accessToken,
        ], 200);
    }

    /**
     * Check if email is already registered
     *
     * @urlParam email address to be checked: currikiuser@curriki.org
     *
     * @param string $email
     * @return Application|ResponseFactory|Response
     * @throws \Throwable
     */
    public function checkEmail($email)
    {
        $user = User::where('email', $email)->first();
        if ($user) {
            return response([
                'exists' => true,
                'message' => "Email is already registered",
            ], 200);
        } else {
            return response([
                'exists' => false,
                'message' => "Email is not registered",
            ], 200);
        }
    }

    /**
     * Wordpress SSO: Get default settings for a particular wordpress sso integration
     *
     * @urlParam client id for the integration: 7PwnyVuYIWJtdKYIzvxBpo5wFAizj12F6WU8qFta
     *
     * @param string $clientId
     * @return Application|ResponseFactory|Response
     * @throws \Throwable
     */
    public function getWordpressSSODefaultSettings($clientId)
    {
        // Checking lms settings
        $default_lms_setting = $this->defaultSsoSettingsRepository->findByField('lti_client_id', $clientId);
        if (!$default_lms_setting) {
            return response([
                'errors' => ['Unable to find default LMS setting with your client id.'],
            ], 404);
        }

        return response(new DefaultSsoSettingsResource($default_lms_setting, true));
    }

    /**
     * Wordpress SSO: Execute wordpress sso authentication
     *
     * @bodyParam clientId string client id for the integration: 7PwnyVuYIWJtdKYIzvxBpo5wFAizj12F6WU8qFta
     * @bodyParam code string temporary token for sso : 7PwnyVuYIWJtdKYIzvxBpo5wFAizj12F6WU8qFta
     *
     * @param WordpressSSOLoginRequest $request
     * @return Application|ResponseFactory|Response
     * @throws \Throwable
     */
    public function wordpressSSO(WordpressSSOLoginRequest $request)
    {
        // Checking lms settings
        $default_lms_setting = $this->defaultSsoSettingsRepository->findByField('lti_client_id', $request->clientId);
        if (!$default_lms_setting) {
            return response([
                'errors' => ['Unable to find default LMS setting with your client id.'],
            ], 404);
        }
        $default_lms_setting_array = $default_lms_setting->toArray();

        // Getting user info from Wordpress
        $client = new Client();
        $data = array(
            "grant_type" => "authorization_code",
            "code" => $request->code,
            "client_id" => $request->clientId,
            "client_secret"=> $default_lms_setting->lms_access_secret
        );
        $tokenUrl = $default_lms_setting->lms_url.'/token';
        try {
            $tokenRequest = $client->post($tokenUrl,  array(
                'form_params' => $data,
                'Content-Type' => 'application/json',
            ));
            $authResponse = json_decode($tokenRequest->getBody(), true);
        } catch (\Exception $e) {
            return response([
                'errors' => ['Unable to login with SSO.' . $e->getMessage()],
            ], 400);
        }

        $meUrl = $default_lms_setting->lms_url.'/me/?access_token='.$authResponse['access_token'];
        try {
            $meRequest = $client->post($meUrl,  array('Content-Type' => 'application/json'));
            $meResponse = json_decode($meRequest->getBody(), true);
        } catch (\Exception $e) {
            return response([
                'errors' => ['Unable to login with SSO.' . $e->getMessage()],
            ], 400);
        }

        // Checking if user exists in studio
        $user = User::where('email', $meResponse['user_email'])->first();

        if (!$user) {
            // Creating user
            $password = Str::random(10);
            $user = $this->userRepository->create([
                'first_name' => $meResponse['display_name'],
                'last_name' => $meResponse['display_name'],
                'email' => $meResponse['user_email'],
                'password' => Hash::make($password),
                'remember_token' => Str::random(64),
                'email_verified_at' => now(),
            ]);
        }

        // Checking if user has lms settings for this client id
        $userLmsSetting = $user->lmssetting()->where('lti_client_id', $request->clientId)->first();

        if(!$userLmsSetting) {
            // Adding lms settings
            $lmsSetting = $default_lms_setting->toArray();
            $lmsSetting['lms_login_id'] = $meResponse['user_email'];
            $user->lmssetting()->create($lmsSetting);
        }

        // Check if user belongs to organization
        if (!$default_lms_setting->organization->users()->where('email', $user->email)->first()) {
            $roleId = $default_lms_setting_array['role_id']
                ? $default_lms_setting_array['role_id']
                : $default_lms_setting->organization->roles()->where('name', 'self_registered')->first()->id;

            $default_lms_setting->organization->users()->attach(
                $user, 
                ['organization_role_type_id' => $roleId]
            );
        }

        return [
            'user' => $user,
            'access_token' => $user->createToken('auth_token')->accessToken,
        ];
    }
}


