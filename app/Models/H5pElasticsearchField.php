<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class H5pElasticsearchField extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'h5p_elasticsearch_fields';

    /**
     * Get the library that contains field.
     */
    public function library()
    {
        return $this->belongsTo('Djoudi\LaravelH5p\Eloquents\H5pLibrary');
    }
}
