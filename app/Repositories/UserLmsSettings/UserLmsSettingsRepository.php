<?php

namespace App\Repositories\UserLmsSettings;

use Illuminate\Support\Facades\Auth;
use App\Repositories\UserLmsSettings\UserLmsSettingsRepositoryInterface;

class UserLmsSettingsRepository implements UserLmsSettingsRepositoryInterface
{
    /**
     * Get all user lms settings
     *
     * @return Collection
     */
    public function all(){
        return Auth::user()->lmssetting;
    }
}
