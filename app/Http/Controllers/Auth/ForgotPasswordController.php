<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Forgot Password Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * @group 1. Authentication
     *
     * Forgot Password
     *
     * Send a password reset link to the given user.
     *
     * @bodyParam email string required The email of a user Example: john.doe@currikistudio.org
     *
     * @response {
     *   "message": "Password reset email has been sent. Please follow the instructions."
     * }
     *
     * @response 400 {
     *   "errors": [
     *     "Email is not verified."
     *   ]
     * }
     *
     * @param Request $request
     * @return Response
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        $user = User::where('email', $request->email)->first();
        if ($user && !$user->email_verified_at) {
            return response([
                'errors' => ['Email is not verified.'],
            ], 400);
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );

        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param Request $request
     * @param string $response
     * @return Response
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        $response = ['message' => 'Password reset email has been sent. Please follow the instructions.'];
        return response($response, 200);
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param Request $request
     * @param string $response
     * @return Response
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        $response = ['error' => Lang::get($response)];
        return response($response, 400);
    }
}
