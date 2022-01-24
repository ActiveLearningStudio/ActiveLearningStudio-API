<?php

namespace App\Models\Integration;

use App\Models\Organization;
use App\Models\Traits\GlobalScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

class KalturaAPISetting extends Model
{
    use SoftDeletes, GlobalScope;
    protected $table = 'kaltura_api_settings';
    /**
     * @author        Asim Sarwar
     * Date           20-01-2022
     * The attributes that are mass assignable.     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'organization_id',
        'partner_id',
        'sub_partner_id',
        'name',
        'email',
        'expiry',
        'session_type',
        'admin_secret',
        'user_secret',
        'privileges',
        'description'
    ];

    /** 
    * @author        Asim Sarwar
    * Date           20-01-2022
    * Detail         Define belongs to relationship with users table,
    * @return        Relationship
    */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /** 
    * @author        Asim Sarwar
    * Date           20-01-2022
    * Detail         Define belongs to relationship with organizations table,
    * @return        Relationship
    */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

}
