<?php

namespace App\Models;

use App\Models\Traits\GlobalScope;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExportRequest extends Model
{
    use SoftDeletes, GlobalScope, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'user_id',
        'type'
    ];

    /**
     * Get the export request items for the export request.
     */
    public function exportRequestsItems()
    {
        return $this->hasMany('App\Models\ExportRequestItem');
    }

    /**
     * Get the organization that owns the export request.
     */
    public function organization()
    {
        return $this->belongsTo('App\Models\Organization');
    }

    /**
     * Cascade on delete the export request
     */
    public static function boot()
    {
        parent::boot();

        self::deleting(function (ExportRequest $exportRequest) {
            $isForceDeleting = $exportRequest->isForceDeleting();

            foreach ($exportRequest->exportRequestsItems as $exportRequestsItem) {
                if ($isForceDeleting) {
                    $exportRequestsItem->forceDelete();
                } else {
                    $exportRequestsItem->delete();
                }
            }
        });
    }
}
