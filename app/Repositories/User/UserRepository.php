<?php

namespace App\Repositories\User;

use App\Http\Resources\V1\NotificationListResource;
use App\Repositories\BaseRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Passport;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Illuminate\Database\Eloquent\Builder;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * UserRepository constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Update the user if soft deleted, otherwise create new
     *
     * @param array $data
     * @return false|Model
     */
    public function create(array $data)
    {
        try {
            $data['deleted_at'] = null;
            return $this->model->withTrashed()->updateOrCreate(['email' => $data['email']], $data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return false;
    }

    /**
     * Search by name
     *
     * @param $name
     * @return mixed
     */
    public function searchByName($name)
    {
        return $this->model->name($name)->get();
    }

    /**
     * Search by name and email
     *
     * @param $key
     * @return mixed
     */
    public function searchByEmailAndName($key)
    {
        return $this->model->searchByEmailAndName($key)->get();
    }

    /**
     * @param $accessToken
     * @return bool
     */
    public function parseToken($accessToken)
    {
        $key_path = Passport::keyPath('oauth-public.key');
        $parseTokenKey = file_get_contents($key_path);

        $token = (new Parser())->parse((string) $accessToken);

        $signer = new Sha256();

        if ($token->verify($signer, $parseTokenKey)) {
            $userId = $token->getClaim('sub');

            return $userId;
        } else {
            return false;
        }
    }

    /**
     * To arrange listing of notifications
     * @param $notifications
     * @return array
     */
    public function fetchListing($notifications)
    {
        $returnNotifications = [];
        $yesterdayNotifications = clone $notifications;
        $olderNotifications = clone $notifications;
        $returnNotifications['today'] = NotificationListResource::collection($notifications->with('notifiable')->whereDate('created_at', Carbon::today())->get());
        $returnNotifications['yesterday'] = NotificationListResource::collection($yesterdayNotifications->with('notifiable')->whereDate('created_at', Carbon::yesterday())->get());
        $returnNotifications['older'] = NotificationListResource::collection($olderNotifications->with('notifiable')->whereDate('created_at', '<', Carbon::yesterday())->get());
        return $returnNotifications;
    }

    /**
     * Check if user has the specified permission in the provided organization
     *
     * @param $user
     * @param $permission
     * @param $organization
     * @return boolean
     */
    public function hasPermissionTo($user, $permission, $organization)
    {
        $hasPermissionTo =  $organization->userRoles()
                            ->wherePivot('user_id', $user->id)
                            ->whereHas('permissions', function (Builder $query) use ($permission) {
                                $query->where('name', '=', $permission);
                            })->get();

        if ($hasPermissionTo->count()) {
            return true;
        } elseif ($organization->parent) {
            return $this->hasPermissionTo($user, $permission, $organization->parent);
        }

        return false;
    }
}
