<?php

namespace App\Repositories\Membership;

use App\User;
use App\Models\ActivityMetric;
use App\Models\Playlist;
use App\Models\Activity;
use App\Models\MembershipType;

class MembershipRepository
{

    // Returns membership details for the specified user
    public function getUserMembership(User $user){
        $projectIds = $user->projects()->pluck('id');
        $playlistIds = Playlist::whereIn('project_id', $projectIds)->pluck('id');
        $activityIds = Activity::whereIn('playlist_id', $playlistIds)->pluck('id');

        return [
            'membership_type_id' => $user->membership->id,
            'membership_type_name' => $user->membership->name,
            'membership_type' => $user->membership->label,
            'membership_description' => $user->membership->description,
            'total_storage' => $user->membership->total_storage,
            'used_storage' => intval(ActivityMetric::whereIn('activity_id', $activityIds)->sum('used_storage')),
            'total_bandwidth' => $user->membership->total_bandwidth,
            'used_bandwidth' => intval(ActivityMetric::whereIn('activity_id', $activityIds)->sum('used_bandwidth')),
            'price' => $user->membership->price
        ];
    }

    public function redeemOffer(User $user, $offerName){

        // Offer redeeming is hardcoded for now
        if($offerName !== 'linodeFREE')
            return ['error'=>'Invalid offer'];

        $demoMembership = MembershipType::where('name', 'demo')->first();
        $freeMembership = MembershipType::where('name', 'free')->first();

        if($user->membership->name == $freeMembership->name)
            return ['error'=>'You already have a '.$freeMembership->label.' account.', 'msg'=>'Redeem failed.'];
        
        if($user->membership->name != $demoMembership->name)
            return ['error'=>'Offer is only valid for users with '.$demoMembership->label.' accounts.', 'msg'=>'Redeem failed.'];

        $user->membership_type_id = $freeMembership->id;
        $user->save();

        return ['msg'=>'Offer redeemed successfully', 'error'=>false];
    }
}
