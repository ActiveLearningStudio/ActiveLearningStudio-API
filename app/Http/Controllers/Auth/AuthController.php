<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

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
     * @param Request $request
     * @return Response
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'organization_name' => 'required|string|min:2|max:50',
            'job_title' => 'required|string|max:255',
        ]);

        $data['password'] = bcrypt($data['password']);
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
     * @param Request $request
     * @return Response
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($data)) {
            return response([
                'errors' => ['Invalid Credentials'],
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
     * Logout
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
