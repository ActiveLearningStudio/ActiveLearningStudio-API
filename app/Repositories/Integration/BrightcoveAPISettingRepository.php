<?php
/**
 * @author        Asim Sarwar
 * Class          BrightcoveAPISettingRepository
 */
namespace App\Repositories\Integration;

use App\Models\Organization;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use App\Models\H5pBrightCoveVideoContents;
use App\Models\Integration\BrightcoveAPISetting;
use App\Repositories\Integration\BrightcoveAPISettingInterface;
use App\User;
use SebastianBergmann\Environment\Console;

class BrightcoveAPISettingRepository extends BaseRepository implements BrightcoveAPISettingInterface
{

    protected $model;
    /**
     * BrightcoveAPISettingRepository constructor
     * @param BrightcoveAPISetting $model
     */
    public function __construct(BrightcoveAPISetting $model)
    {
        $this->model = $model;
    }

    /**
     * To get list of brightcove account/setting   
     * @param  integer $suborganization
     * @return mixed
     * @throws GeneralException
     */
    public function getAccountListByOrg($suborganization)
    {
        $query = $this->model->with(['user', 'organization']);
        $settings = $query->where('organization_id', $suborganization)->get();
        if (!$settings->isEmpty()) {
            return $settings;
        }
        throw new GeneralException('Brightcove account list not found.');
    }

    /**
     * To get row record of brightcove account/setting
     * @param integer $suborganization, $id
     * @return mixed
     * @throws GeneralException
     */
    public function getRowRecordByOrgId($suborganization, $id)
    {
        $setting = $this->model->where('organization_id', $suborganization)->find($id);
        if ($setting) {
            return $setting;
        }
        throw new GeneralException('Brightcove API setting not found.');
    }

    /**
     * @param array $data, integer $suborganization
     * @return mixed
     */
    public function getAll($data, $suborganization)
    {
        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');
        $query = $this->model->with(['user', 'organization']);
        if (isset($data['query']) && $data['query'] !== '') {
            $query->where(function ($query) use ($data) {
                $query = $query->whereHas('user', function ($qry) use ($data) {
                    $qry->where('email', 'iLIKE', '%' . $data['query'] . '%');
                });
                $query->orWhere('account_id', 'iLIKE', '%' . $data['query'] . '%');
                $query->orWhere('account_name', 'iLIKE', '%' . $data['query'] . '%');
                $query->orWhere('account_email', 'iLIKE', '%' . $data['query'] . '%');
            });
        }
        if (isset($data['order_by_column']) && $data['order_by_column'] !== '')
        {
            $orderByType = isset($data['order_by_type']) ? $data['order_by_type'] : 'ASC';
            $query->orderBy($data['order_by_column'], $orderByType);
        } else {
            $query->orderBy('id', 'DESC');
        }
        return $query->where('organization_id', $suborganization->id)->paginate($perPage);
    }

    /**
     * @param  array $data
     * @return mixed
     * @throws GeneralException
     */
    public function create($data)
    {
        try {
            if ($createSetting = $this->model->create($data)) {
                return ['message' => 'Brightcove API setting created successfully!', 'data' => $createSetting];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to create Brightcove API setting, please try again later!');
    }

    /**
     * @param  array $data, integer $id
     * @return mixed
     * @throws GeneralException
     */
    public function update($id, $data)
    {
        try {
            if ($this->find($id)->update($data)) {
                return ['message' => 'Brightcove API setting updated successfully!', 'data' => $this->find($id)];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to update Brightcove API setting, please try again later!');
    }

    /**
     * @param  integer $id
     * @return mixed
     * @throws GeneralException
     */
    public function find($id)
    {
        if ($setting = $this->model->find($id)) {
            return $setting;
        }
        throw new GeneralException('Brightcove API setting not found.');
    }

    /**
     * @param  integer $id
     * @return mixed
     * @throws GeneralException
     */

    public function getTitleList(Collection  $videos) : Array
    {
        $title = [];
        foreach($videos as $video){
            $user = User::where('id', $video->activities[0]['user_id'])->first();
            array_push($title, (object)["title"=> $video->activities[0]['title'], 
                "user_name" => $user->first_name." ".$user->last_name ]);
        }
        return $title;
    }

    public function destroy($id, $orgId)
    {
        try {
            $videos = H5pBrightCoveVideoContents::with('activities')->whereHas('activities', function ($query) use($orgId) {
                    $query->where('organization_id', $orgId)
                          ->where('user_id', '>', 0);
                })->where('brightcove_api_setting_id', $id)->get();
            if($videos->isNotEmpty()){
                $title = $this->getTitleList($videos);
                if($title !== null){
                    $deleteMessage = "";
                    foreach($title as $videoTitle){
                        $deleteMessage .= "'".$videoTitle->title."'".' created by '."'".$videoTitle->user_name."'. ";
                    }
                    throw new GeneralException("Cannot delete! This API settings has following Videos: ".$deleteMessage);
                }
            }
            $this->find($id)->delete();
            return ['message' => 'Brightcove API setting deleted!', 'data' => []];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException($e->getMessage());
    }

    /**
     * To get record by account id
     * @param integer $suborganization, $id
     * @return mixed
     * @throws GeneralException
     */
    public function getByAccountId($accountId)
    {
        $setting = $this->model->where('account_id', $accountId)->first();
        if ($setting) {
            return $setting;
        }
        throw new GeneralException('Brightcove API setting not found.');
    }

    /**
     * To get record by setting id
     * @param integer $suborganization, $id
     * @return mixed
     * @throws GeneralException
     */
    public function getById($id)
    {
        $setting = $this->model->where('id', $id)->first();
        if ($setting) {
            return $setting;
        }
        throw new GeneralException('Brightcove API setting not found.');
    }
}
