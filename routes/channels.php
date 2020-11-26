<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('user-channel.{userId}', function ($user, $userId) {
    Log::info("User id : ". $user->id);
    Log::info("userId : ". $userId);
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('project-update', function () {
    return true;
});

Broadcast::channel('playlist-update', function () {
    return true;
});

Broadcast::channel('activity-update', function () {
    return true;
});
