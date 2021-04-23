<?php

namespace App\Repositories\InvitedGroupUser;

use App\Models\InvitedGroupUser;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvitedGroupUserRepository extends BaseRepository implements InvitedGroupUserRepositoryInterface
{
    /**
     * CachedUserRepository constructor.
     *
     * @param InvitedGroupUser $model
     */
    public function __construct(InvitedGroupUser $model)
    {
        parent::__construct($model);
    }

    /**
     * Update the unregistered email if added before, otherwise create new
     *
     * @param array $data
     * @return false|Model
     */
    public function create(array $data)
    {
        try {
            return $this->model->updateOrCreate(['invited_email' => $data['invited_email'], 'group_id' => $data['group_id']], $data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return false;
    }

    /**
     * Delete the invited user after account created
     *
     * @param $email
     */
    public function delete($email)
    {
        $this->model->where('invited_email', $email)->delete();
    }

    /**
     * Search by email
     *
     * @param $email
     * @return mixed
     */
    public function searchByEmail($email)
    {
        return $this->model->searchByEmail($email)->get();
    }

}
