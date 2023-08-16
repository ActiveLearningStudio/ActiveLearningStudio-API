<?php

namespace App\Models;

use Illuminate\Http\Request;
use App\Models\Traits\GlobalScope;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExportRequestItem extends Model
{
    use SoftDeletes, GlobalScope, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'export_request_id',
        'parent_id',
        'item_id',
        'item_type',
        'exported_file_path'
    ];

    /**
     * Get the sub export request items for the export request.
     */
    public function subExportRequestsItems()
    {
        return $this->hasMany('App\Models\ExportRequestItem', 'parent_id');
    }

    /**
     * Get the export request that owns the export request item.
     */
    public function exportRequest()
    {
        return $this->belongsTo('App\Models\ExportRequest');
    }

    /**
     * Get the user that owns the export request item.
     */
    public function user()
    {
        return $this->exportRequest->organization->users->find($this->item_id);
    }

    /**
     * Cascade on delete the project
     */
    public static function boot()
    {
        parent::boot();

        self::deleting(function (ExportRequestItem $exportRequestItem) {
            $isForceDeleting = $exportRequestItem->isForceDeleting();

            if ($isForceDeleting && File::exists(public_path('/storage/exports/export-requests/' . $exportRequestItem->exported_file_path))) {
                File::delete(public_path('/storage/exports/export-requests/' . $exportRequestItem->exported_file_path));
            }
        });
    }
}
