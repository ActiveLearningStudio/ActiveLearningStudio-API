<?php

namespace App\Repositories\MediaSources;

use App\Exceptions\GeneralException;
use App\Models\MediaSource;
use App\Models\Organization;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Log;
use App\Repositories\MediaSources\MediaSourcesInterface;

class MediaSourcesRepository extends BaseRepository implements MediaSourcesInterface
{

    /**
     * MediaSourcesRepository constructor
     * @param MediaSource $model
     */
    public function __construct(MediaSource $model)
    {
        $this->model = $model;
    }

    /**
     * @param $data array
     * @return mixed
     */
    public function getAll($data)
    {       
        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');
        $query = $this->model;
        if (isset($data['query']) && $data['query'] !== '') {
            $query->where(function ($query) use ($data) {
                $query->orWhere('name', 'iLIKE', '%' . $data['query'] . '%');
                $query->orWhere('media_type', 'iLIKE', '%' . $data['query'] . '%');
            });
        }
        if (isset($data['order_by_column']) && $data['order_by_column'] !== '')
        {
            $orderByType = isset($data['order_by_type']) ? $data['order_by_type'] : 'ASC';
            $query->orderBy($data['order_by_column'], $orderByType);
        }
        if (isset($data['filter']) && $data['filter'] !== '') {
            $query = $query->where('media_type', $data['filter']);
        }
        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * @param $data array
     * @return mixed
     * @throws GeneralException
     */
    public function create($data)
    {
        try {
            if ($createSetting = $this->model->create($data)) {
                return ['message' => 'Media Source created successfully!', 'data' => $createSetting];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to create media source, please try again later!');
    }

    /**
     * @param $data array
     * @param $id int
     * @return mixed
     * @throws GeneralException
     */
    public function update($id, $data)
    {
        try {
            if ($this->find($id)->update($data)) {
                return ['message' => 'Media Source updated successfully!', 'data' => $this->find($id)];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to update media source, please try again later!');
    }

    /**
     * @param $id int
     * @return mixed
     * @throws GeneralException
     */
    public function find($id)
    {
        if ($setting = $this->model->find($id)) {
            return $setting;
        }
        throw new GeneralException('Media Source not found.');
    }

    /**
     * @param $id int
     * @return mixed
     * @throws GeneralException
     */
    public function destroy($id)
    {
        try {
            $this->find($id)->delete();
            return ['message' => 'Media Source deleted!', 'data' => []];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to delete media source, please try again later!');
    }

    /**
     * To get media source id by name
     *
     * @param $mediaSourceName string
     * @return int
     */
    public function getMediaSourceIdByName($mediaSourceName)
    {
        $mediaSourcesRow = $this->model->where('name', $mediaSourceName)
                              ->where('media_type', 'Video')
                              ->first();
        $mediaSourcesId = !empty($mediaSourcesRow) ? $mediaSourcesRow->id : 0;
        return $mediaSourcesId;
    }
    
}
