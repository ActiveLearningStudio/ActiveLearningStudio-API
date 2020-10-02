<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Reset Password Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * @group 1. Authentication
     *
     * Reset Password
     *
     * Reset the given user's password.
     *
     * @bodyParam token string required The token for reset password Example: ya29.a0AfH6SMBx-CIZfKRorxn8xPugO...
     * @bodyParam email string required The email of a user Example: john.doe@currikistudio.org
     * @bodyParam password string required The new password Example: Password123
     * @bodyParam password_confirmation string required The confirmation of password Example: Password123
     *
     * @response {
     *   "message": "Password has been reset successfully."
     * }
     *
     * @response 401 {
     *   "error": "Invalid request."
     * }
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPass(Request $request)
    {
        return $this->reset($request);
    }

    /**
     * Reset Password
     *
     * @param User $user
     * @param string $password
     */
    protected function resetPassword(User $user, string $password)
    {
        $user->password = Hash::make($password);
        $user->save();
        event(new PasswordReset($user));
    }

    /**
     * Send reset password response
     *
     * @param Request $request
     * @param $response
     * @return Response
     */
    protected function sendResetResponse(Request $request, $response)
    {
        $response = ['message' => 'Password has been reset successfully.'];
        return response($response, 200);
    }

    /**
     * Send reset password failed response
     *
     * @param Request $request
     * @param $response
     * @return Response
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        $response = ['error' => 'Invalid request.'];
        return response($response, 401);
    }
}
