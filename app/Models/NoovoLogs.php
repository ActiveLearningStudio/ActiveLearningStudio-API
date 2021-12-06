<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoovoLogs extends Model
{
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'push_to_noovo_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'team_id',
        'noovo_company_id',
        'noovo_company_title',
        'noovo_team_id',
        'noovo_team_title',
        'projects',
        'response',
        'status'
    ];
}
