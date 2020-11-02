<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\V1\UserResource;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\UserLogin\UserLoginRepositoryInterface;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @group 1. Authentication
 *
 * APIs for Authentication
 */
class AuthController extends Controller
{
    private $userRepository;
    private $userLoginRepository;

    /**
     * AuthController constructor.
     *
     * @param UserRepositoryInterface $userRepository
     * @param UserLoginRepositoryInterface $userLoginRepository
     */
    public function __construct(UserRepositoryInterface $userRepository, UserLoginRepositoryInterface $userLoginRepository)
    {
        $this->userRepository = $userRepository;
        $this->userLoginRepository = $userLoginRepository;
    }

    /**
     * Register
     *
     * @bodyParam first_name string required First name of a user Example: John
     * @bodyParam last_name string required Last name of a user Example: Doe
     * @bodyParam email string required Email of a user Example: john.doe@currikistudio.org
     * @bodyParam password string required Password Example: Password123
     * @bodyParam organization_name string Organization name of a user Example: Curriki
     * @bodyParam job_title string Job title of a user Example: Developer
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

        $data['password'] = Hash::make($data['password']);
        $data['remember_token'] = Str::random(64);
        $data['email_verified_at'] = now();

        $user = $this->userRepository->create($data);

        if ($user) {
            event(new Registered($user));

//            return response([
//                'message' => "You are one step away from building the world's most immersive learning experiences with CurrikiStudio!<br>Check your email and follow the instructions to verify your account!"
//            ], 201);
            return response([
                'message' => "You are one step away from building the world's most immersive learning experiences with CurrikiStudio!"
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

        if (!auth()->attempt($data)) {
            return response([
                'errors' => ['Invalid Credentials.'],
            ], 400);
        }

        $user = auth()->user();

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
    public function loginWithGoogle(Request $request)
    {
        $client = new \Google_Client();
        $client->setClientId(config('google.gapi_client_id'));
        $result = $client->verifyIdToken($request->tokenId);

        if ($result) {
            $user = $this->userRepository->findByField('email', $result['email']);
            if (!$user) {
                $password = Str::random(10);
                $user = $this->userRepository->create([
                    'first_name' => $result['name'],
                    'last_name' => ' ',
                    'email' => $result['email'],
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(64),
                    'email_verified_at' => now(),
                ]);
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

}
