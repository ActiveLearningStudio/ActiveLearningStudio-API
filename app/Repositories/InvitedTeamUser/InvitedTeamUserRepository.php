<?php

namespace App\Repositories\InvitedTeamUser;

use App\Models\InvitedTeamUser;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvitedTeamUserRepository extends BaseRepository implements InvitedTeamUserRepositoryInterface
{
    /**
     * CachedUserRepository constructor.
     *
     * @param InvitedTeamUser $model
     */
    public function __construct(InvitedTeamUser $model)
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
            return $this->model->updateOrCreate(['invited_email' => $data['invited_email'], 'team_id' => $data['team_id']], $data);
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
