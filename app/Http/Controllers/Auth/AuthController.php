<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\V1\UserResource;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @group Authentication
 *
 * APIs for Authentication
 */
class AuthController extends Controller
{
    private $userRepository;

    /**
     * AuthController constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register
     *
     * @bodyParam first_name string required First name of user
     * @bodyParam last_name string required Last name of user
     * @bodyParam email string required Email of user
     * @bodyParam password string required Password
     * @bodyParam organization_name string required Organization name of user
     * @bodyParam job_title string required Job title of user
     *
     * @response 201 {
     *   "message": "Verification email is sent. Please follow the instructions."
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

        $user = $this->userRepository->create($data);

        if ($user) {
            event(new Registered($user));

            return response([
                'message' => 'Verification email is sent. Please follow the instructions.'
            ], 201);
        }

        return response([
            'errors' => ['Could not create user account. Please try again later.'],
        ], 500);
    }

    /**
     * Login
     *
     * @bodyParam email string required The email of user
     * @bodyParam password string required The password corresponded the email
     *
     * @response {
     *   "user": {
     *     "id": 1,
     *     "first_name": "Test",
     *     "last_name": "User",
     *     "name": "test@test.com",
     *     "email": "test@test.com",
     *     "organization_name": "Curriki",
     *     "organization_type": null,
     *     "job_title": "Developer",
     *     "address": null,
     *     "phone_number": null,
     *     "website": null,
     *     "hubspot": false,
     *     "subscribed": true,
     *     "created_at": "2020-08-26T11:28:16.000000Z",
     *     "updated_at": "2020-09-10T15:08:45.000000Z",
     *     "gapi_access_token": null
     *   },
     *   "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiODBjMGJhY..."
     * }
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid Credentials"
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

        return response([
            'user' => new UserResource($user),
            'access_token' => $accessToken,
        ], 200);
    }

    /**
     * Login with Google
     *
     * @response {
     *   "user": {
     *     "id": 1,
     *     "first_name": "Test",
     *     "last_name": "User",
     *     "name": "test@test.com",
     *     "email": "test@test.com",
     *     "organization_name": "Curriki",
     *     "organization_type": null,
     *     "job_title": "Developer",
     *     "address": null,
     *     "phone_number": null,
     *     "website": null,
     *     "hubspot": false,
     *     "subscribed": true,
     *     "created_at": "2020-08-26T11:28:16.000000Z",
     *     "updated_at": "2020-09-10T15:08:45.000000Z",
     *     "gapi_access_token": JSON Object
     *   },
     *   "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiODBjMGJhY..."
     * }
     *
     * @response 400 {
     *   "errors": [
     *     "Unable to login with Google"
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
                    'last_name' => '',
                    'email' => $result['email'],
                    'password' => Hash::make($password),
                    'temp_password' => $password,
                    'remember_token' => Str::random(64),
                    'organization_name' => ' ',
                    'job_title' => ' ',
                ]);
            }
            $user->gapi_access_token = $request->tokenObj;
            $user->save();

            $accessToken = $user->createToken('auth_token')->accessToken;

            return response([
                'user' => new UserResource($user),
                'access_token' => $accessToken,
            ], 200);
        }

        return response([
            'errors' => ['Unable to login with Google'],
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
}
