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
        return $this->model->name($name)->paginate();
    }

    /**
     * Search by name and email
     *
     * @param $key
     * @return mixed
     */
    public function searchByEmailAndName($key)
    {
        return $this->model->searchByEmailAndName($key)->paginate();
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

    /**
     * Check if user has the specified permission in the provided team role
     *
     * @param $user
     * @param $permission
     * @param $team
     * @return boolean
     */
    public function hasTeamPermissionTo($user, $permission, $team)
    {
        if (is_null($team)) {
            return true;
        }
        $hasTeamPermissionTo =  $team->userRoles()
                            ->wherePivot('user_id', $user->id)
                            ->whereHas('permissions', function (Builder $query) use ($permission) {
                                $query->where('name', '=', $permission);
                            })->get();

        if ($hasTeamPermissionTo->count()) {
            return true;
        }

        return false;
    }

    /**
     * Users basic report, projects, playlists and activities count
     * @param $data
     * @return mixed
     */
    public function reportBasic($data)
    {
        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');
        $q = $data['query'] ?? null;

        $this->query = $this->model->select(['id', 'first_name', 'last_name', 'email'])->withCount(['projects', 'playlists', 'activities'])
            ->when($data['mode'] === 'subscribed', function ($query) {
                return $query->where(function ($query) {
                    return $query->where('subscribed', true);
                });
            });

        if ($q) {
            $this->query->where(function($qry) use ($q) {
                $qry->orWhere('first_name', 'iLIKE', '%' .$q. '%');
                $qry->orWhere('last_name', 'iLIKE', '%' .$q. '%');
                $qry->orWhere('email', 'iLIKE', '%' .$q. '%');
            });
        }

        return $this->query->paginate($perPage)->appends(request()->query());
    }

    /**
     * To get exported project list of last 10 days
     * @param $days_limit
     * @return array
     */
    public function getUsersExportProjectList($days_limit)
    {
        $date = Carbon::now()->subDays($days_limit);

        $user_export_notifications = auth()->user()->notifications()
                                                        ->where('type', 'App\Notifications\ProjectExportNotification')
                                                        ->where('created_at', '>=', $date)->get();

        $return_exported_list = [];
        foreach ($user_export_notifications as $exported_project) {

            if(!isset($exported_project->data['file_name'])) continue; // skip if file_name param not exist in table

            if(!file_exists(storage_path('app/public/exports/' . $exported_project->data['file_name']))) continue; // skip if file not exist in directory

            $return_project = [];
            $return_project['project'] = isset($exported_project->data['project']) ? $exported_project->data['project'] : "" ;
            $return_project['created_at'] = Carbon::parse($exported_project->created_at)
                                                                                    ->format(config('constants.default-date-format'));
            $return_project['will_expire_on'] = Carbon::parse($exported_project->created_at)->addDays(config('constants.default-exported-projects-days-limit'))
                                                                                    ->format(config('constants.default-date-format'));
            $return_project['link'] = isset($exported_project->data['link']) ? $exported_project->data['link'] : "";
            array_push($return_exported_list, $return_project);
        }

        return $return_exported_list;
    }

    public function getFirstUser()
    {
        return $this->model->orderBy('id', 'asc')->first();
    }
}
