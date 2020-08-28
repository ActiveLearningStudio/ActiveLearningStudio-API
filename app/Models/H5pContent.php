<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class H5pContent extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'h5p_contents';

    /**
     * Get the activity record associated with the h5p content.
     */
    public function activity()
    {
        return $this->hasOne('App\Models\Activity');
    }
}
