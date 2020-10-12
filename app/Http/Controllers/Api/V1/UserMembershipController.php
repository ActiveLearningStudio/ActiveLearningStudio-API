<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\Membership\MembershipRepository;
use App\User;

use Illuminate\Http\Response;

class UserMembershipController extends Controller
{
    private $MembershipRepository;

    public function __construct(MembershipRepository $MembershipRepository)
    {
        $this->MembershipRepository = $MembershipRepository;
    }

    // Returns membership details for a particular user
    public function show(User $user)
    {
        $authenticated_user = auth()->user();

        if (!$authenticated_user->isAdmin() && $authenticated_user->id != $user->id) {
            return response([
                'errors' => ['Unauthorized.'],
            ], 401);
        }

        return response([
            'membership' => $this->MembershipRepository->getUserMembership($user),
        ], 200);
    }

    public function redeemOffer($offerName)
    {
        $user = auth()->user();
        $result = $this->MembershipRepository->redeemOffer($user, $offerName);

        if ($result['error']) {
            return response(['errors' => [$result['error']]], 401);
        }

        return response(['success'], 200);
    }
}
