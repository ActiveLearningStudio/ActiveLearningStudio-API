<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class H5pLibrary extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'h5p_libraries';

    /**
     * Get the fields for the library.
     */
    public function fields()
    {
        return $this->hasMany('App\Models\H5pElasticsearchField', 'library_id');
    }
}
