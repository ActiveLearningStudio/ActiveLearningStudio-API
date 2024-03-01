<?php

namespace App\Repositories\C2E\MediaCatalog;

use App\Exceptions\GeneralException;
use App\Models\C2E\MediaCatalog\MediaCatalogAPISetting;
use App\Models\C2E\MediaCatalog\MediaCatalogSrtContent;
use App\Repositories\C2E\MediaCatalog\MediaCatalogAPISettingInterface;
use App\Models\Organization;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Log;

/**
 * @author        Asim Sarwar
 * Class          MediaCatalogAPISettingRepository
 */
class MediaCatalogAPISettingRepository extends BaseRepository implements MediaCatalogAPISettingInterface
{
    /**
     * Media Catalog Srt Content model object 
     */
    private $mediaCatalogSrtContentModel;

    /**
     * MediaCatalogAPISettingRepository constructor
     * @param MediaCatalogAPISetting $model
     * @param MediaCatalogSrtContent $mediaCatalogSrtContentModel
     */
    public function __construct(MediaCatalogAPISetting $model, MediaCatalogSrtContent $mediaCatalogSrtContentModel)
    {
        parent::__construct($model);
        $this->mediaCatalogSrtContentModel = $mediaCatalogSrtContentModel;
    }

    /**
     * Get all media catalog api settings
     *
     * @param array $data
     * @param Organization $suborganization
     * 
     * @return mixed
     */
    public function getAll($data, $suborganization)
    {
        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');
        $query = $this->model->with(['organization', 'mediaSources']);
        if (isset($data['query']) && $data['query'] !== '') {
            $query->where(function ($query) use ($data) {
                $query->orWhere('name', 'iLIKE', '%' . $data['query'] . '%');
                $query->orWhere('api_setting_id', 'iLIKE', '%' . $data['query'] . '%');
                $query->orWhere('email', 'iLIKE', '%' . $data['query'] . '%');
                $query->orWhere('url', 'iLIKE', '%' . $data['query'] . '%');
            });
        }
        if (isset($data['order_by_column']) && $data['order_by_column'] !== '')
        {
            $orderByType = isset($data['order_by_type']) ? $data['order_by_type'] : 'ASC';
            $query->orderBy($data['order_by_column'], $orderByType);
        } else {
            $query->orderBy('id', 'DESC');
        }

        if (isset($data['filter']) && $data['filter'] > 0) {
            $query = $query->whereHas('mediaSources', function ($qry) use ($data) {
                $qry->where('id', $data['filter']);
            });
        }
        return $query->with(['user', 'organization'])
                     ->where('organization_id', $suborganization->id)
                     ->paginate($perPage)->withQueryString();
    }

    /**
     * Create media catalog api settings
     *
     * @param array $data
     * 
     * @return mixed
     * 
     * @throws GeneralException
     */
    public function create($data)
    {
        try {
            if ($createSetting = $this->model->create($data)) {
                return ['message' => 'Media catalog api setting created successfully!', 'data' => $createSetting];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to create media catalog api setting, please try again later!');
    }

    /**
     * Update media catalog api settings
     *
     * @param MediaCatalogAPISetting $setting
     * @param array $data
     * 
     * @return mixed
     * 
     * @throws GeneralException
     */
    public function update($setting, $data)
    {
        try {
            if ($setting->update($data)) {
                return ['message' => 'Media catalog api setting updated successfully!', 'data' => $this->find($setting->id)];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to update media catalog api setting, please try again later!');
    }

    /**
     * Find media catalog api settings by id
     *
     * @param int $id
     * 
     * @return mixed
     * 
     * @throws GeneralException
     */
    public function find($id)
    {
        if ($setting = $this->model->find($id)) {
            return $setting;
        }
        throw new GeneralException('Media catalog api setting not found.');
    }

    /**
     * Delete media catalog api settings
     *
     * @param MediaCatalogAPISetting $setting
     * 
     * @return mixed
     * 
     * @throws GeneralException
     */
    public function destroy($setting)
    {
        try {
            $setting->delete();
            return ['message' => 'Media catalog api setting deleted!', 'data' => []];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to delete media catalog api setting, please try again later!');
    }

    /**
     * To get row record by org and source type match
     *
     * @param $orgId integer
     * @param $mediaSourcesId int
     * @return object
     * @throws GeneralException
     */
    public function getRowRecordByOrgAndSourceType($orgId, $mediaSourcesId)
    {
        try {            
            return $this->model->where([['organization_id','=', $orgId],['media_source_id','=', $mediaSourcesId]])->first();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Create media catalog video srt content
     *
     * @param array $data
     * 
     * @return mixed
     * 
     * @throws GeneralException
     */
    public function createMediaCatalogSrtContent($data)
    {
        try {
            if ($createSetting = $this->mediaCatalogSrtContentModel->create($data)) {
                return ['message' => 'Media catalog video srt content created successfully!', 'data' => $createSetting];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to create media catalog video srt content, please try again later!');
    }

    /**
     * Get media catalog srt search based record
     *
     * @param int apiSettingId, string $srtSearch
     * 
     * @return mixed
     * 
     * @throws GeneralException
     */
    public function getMediaCatalogSrtSearchRecord($apiSettingId, $srtSearch)
    {
        try {
            $results = $this->mediaCatalogSrtContentModel::
                            whereRaw("to_tsvector('english', content) @@ plainto_tsquery('english', ?)", [$srtSearch])
                            ->where('media_catalog_api_setting_id', $apiSettingId)
                            ->get();
            return $results;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to find media catalog srt conent search record!');
    }
}
