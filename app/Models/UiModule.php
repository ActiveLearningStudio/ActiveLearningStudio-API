<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UiModule extends Model
{
    /**
     * Get the ui sub modules for the ui module.
     */
    public function uiSubModules()
    {
        return $this->hasMany('App\Models\UiModule', 'parent_id');
    }

    /**
     * Get the ui module permissions for the ui module.
     */
    public function uiModulePermissions()
    {
        return $this->hasMany('App\Models\UiModulePermission');
    }
}
