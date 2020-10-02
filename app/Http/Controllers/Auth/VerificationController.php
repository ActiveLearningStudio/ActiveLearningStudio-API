<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EmailVerificationRequest;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * @group 1. Authentication
     *
     * Verify an Email Address
     *
     * Mark the authenticated user's email address as verified.
     *
     * @bodyParam id int required The Id of a user Example: 1
     * @bodyParam hash string required The hash string Example: 9e0f70124a2a88d5435...
     * @bodyParam signature string required The signature Example: 467fbe9a00e7d367553f...
     * @bodyParam expires int required The expire time of verification email Example: 1599754915
     *
     * @response 204 {
     * }
     *
     * @param EmailVerificationRequest $emailVerificationRequest
     * @return Response
     * @throws AuthorizationException
     */
    public function verify(EmailVerificationRequest $emailVerificationRequest)
    {
        $data = $emailVerificationRequest->validated();

        $user = User::find($data['id']);

        if (!$user) {
            throw new AuthorizationException;
        }

        if (! hash_equals((string) $data['hash'], sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {
            return new Response('', 204);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($emailVerificationRequest->user()));
        }

        if ($response = $this->verified($emailVerificationRequest)) {
            return $response;
        }

        return new Response($data, 204);
    }
}
