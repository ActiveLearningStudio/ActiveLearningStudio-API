<?php

namespace App\Repositories\Membership;

use App\User;
use App\Models\ActivityMetric;
use App\Models\Playlist;
use App\Models\Activity;

class MembershipRepository
{

    // Returns membership details for the specified user
    public function getUserMembership(User $user){
        $projectIds = $user->projects()->pluck('id');
        $playlistIds = Playlist::whereIn('project_id', $projectIds)->pluck('id');
        $activityIds = Activity::whereIn('playlist_id', $playlistIds)->pluck('id');

        return [
            'membership_type_id' => $user->membership->id,
            'membership_type' => $user->membership->label,
            'membership_description' => $user->membership->description,
            'total_storage' => $user->membership->total_storage,
            'used_storage' => ActivityMetric::whereIn('activity_id', $activityIds)->sum('used_storage'),
            'total_bandwidth' => $user->membership->total_bandwidth,
            'used_bandwidth' => ActivityMetric::whereIn('activity_id', $activityIds)->sum('used_bandwidth'),
            'price' => $user->membership->price
        ];
    }
}