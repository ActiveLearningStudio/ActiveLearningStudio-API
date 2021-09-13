<?php

namespace App\Models\CurrikiGo;

use Illuminate\Database\Eloquent\Model;

class DefaultLmsSettings extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lms_settings_id',
        'lti_client_id'
    ];

    public function lmssetting()
    {
        return $this->belongsTo(lmssetting::class, 'lms_settings_id');
    }
}
