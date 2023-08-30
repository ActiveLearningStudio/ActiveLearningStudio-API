<?php

namespace App\Repositories\User;

use App\Http\Resources\V1\NotificationListResource;
use App\Repositories\BaseRepository;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Repositories\IndependentActivity\IndependentActivityRepositoryInterface;
use App\Repositories\UiOrganizationPermissionMapping\UiOrganizationPermissionMappingRepositoryInterface;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use App\Repositories\Playlist\PlaylistRepositoryInterface;
use App\CurrikiGo\Moodle\Playlist as PlaylistMoodle;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Passport;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use Lcobucci\JWT\UnencryptedToken;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Exceptions\GeneralException;
use GuzzleHttp\Client;
use App\Http\Resources\V1\UserExportResource;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    private $projectRepository;
    private $independentActivityRepository;
    private $uiOrganizationPermissionMappingRepository;
    private $lmsSettingRepository;
    private $playlistRepository;

    /**
     * UserRepository constructor.
     *
     * @param User $model
     * @param ProjectRepositoryInterface $projectRepository
     * @param IndependentActivityRepositoryInterface $independentActivityRepository
     * @param UiOrganizationPermissionMappingRepositoryInterface $uiOrganizationPermissionMappingRepository
     * @param LmsSettingRepositoryInterface $lmsSettingRepository
     * @param PlaylistRepositoryInterface $playlistRepository
     */
    public function __construct(
        User $model,
        ProjectRepositoryInterface $projectRepository,
        IndependentActivityRepositoryInterface $independentActivityRepository,
        UiOrganizationPermissionMappingRepositoryInterface $uiOrganizationPermissionMappingRepository,
        LmsSettingRepositoryInterface $lmsSettingRepository,
        PlaylistRepositoryInterface $playlistRepository
    )
    {
        $this->projectRepository = $projectRepository;
        $this->independentActivityRepository = $independentActivityRepository;
        $this->uiOrganizationPermissionMappingRepository = $uiOrganizationPermissionMappingRepository;
        $this->lmsSettingRepository = $lmsSettingRepository;
        $this->playlistRepository = $playlistRepository;
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
        $parser = new Parser(new JoseEncoder());

        try {
            $token = $parser->parse($accessToken);
        } catch (CannotDecodeContent | InvalidTokenStructure | UnsupportedHeaderFound $e) {
            echo 'Oh no, an error: ' . $e->getMessage();
        }
        assert($token instanceof UnencryptedToken);

        return $token->claims()->get('sub');
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
     * @param $data
     * @param $suborganization
     * @return mixed
     */
    public function getUsersExportProjectList($data, $suborganization)
    {
        $days_limit = isset($data['days_limit']) ? $data['days_limit'] : config('constants.default-exported-projects-days-limit');
        
        $date = Carbon::now()->subDays($days_limit);

        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');
        $query = auth()->user()->notifications();
        $q = $data['query'] ?? null;
        // if simple request for getting project listing with search
        if ($q) {
            $query = $query->where(function($qry) use ($q) {
                $qry->where('data', 'iLIKE', '%' .$q. '%');
            });
        }
        
        $query =  $query->where('type', 'App\Notifications\ProjectExportNotification');
        $query =  $query->where('created_at', '>=', $date);
        $query =  $query->where('organization_id', $suborganization->id);

        if (isset($data['order_by_column']) && $data['order_by_column'] !== '') {
            $orderByType = isset($data['order_by_type']) ? $data['order_by_type'] : 'ASC';
            $query = $query->orderBy($data['order_by_column'], $orderByType);
        }
        
        return  $query->paginate($perPage)->withQueryString();
    }

    /**
     * To get exported project list of last 10 days
     * @param $suborganization
     * @param $data
     * @return mixed
     */
    public function getUsersExportIndependentActivitiesList($suborganization, $data)
    {
        $days_limit = isset($data['days_limit']) ? $data['days_limit'] : config('constants.default-exported-independent-activities-days-limit');
        
        $date = Carbon::now()->subDays($days_limit);

        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');
        $query = auth()->user()->notifications();
        $q = $data['query'] ?? null;
        // if simple request for getting project listing with search
        if ($q) {
            $query = $query->where(function($qry) use ($q) {
                $qry->where('data', 'iLIKE', '%' .$q. '%');
            });
        }
        
        $query =  $query->where('type', 'App\Notifications\ActivityExportNotification');
        $query =  $query->where('created_at', '>=', $date);
        $query =  $query->where('organization_id', $suborganization->id);

        if (isset($data['order_by_column']) && $data['order_by_column'] !== '') {
            $orderByType = isset($data['order_by_type']) ? $data['order_by_type'] : 'ASC';
            $query = $query->orderBy($data['order_by_column'], $orderByType);
        }
        
        return  $query->paginate($perPage)->withQueryString();
    }

    public function getFirstUser()
    {
        return $this->model->orderBy('id', 'asc')->first();
    }

    /**
     * Command will detect the multiple user with multiple signups, then we take the ids of signup with lowercase 
     * email and replace in all tables on the signup id of uppercase email, then we delete the user record with the uppercase email
     */
    public function eliminateDualSignup()
    {
        $res = DB::select('SELECT * FROM users WHERE LOWER(email) IN (SELECT LOWER(email) AS usr_email FROM users 
        GROUP BY usr_email HAVING COUNT(*) > 1) ORDER BY email');

        $storeResponnse = false;
        $doneRows = array();
        $toRetain = array();
        $toDestroy = array();
        $dataRecord = array();

        foreach ($res as $key => $oneEmail) {
            if (!in_array(strtolower($oneEmail->email), $doneRows)) {
                $duplicateUsers = $this->model->where('email', 'ilike', $oneEmail->email)->orderBy('email')->get();

                if ($duplicateUsers && count($duplicateUsers) > 1) {

                    $toRetain = $duplicateUsers[0]['email'];
                    $toDestroy = $duplicateUsers[1]['email'];

                    $toRetainId = $duplicateUsers[0]['id'];
                    $toDestroyId = $duplicateUsers[1]['id'];

                    $doneRows[] = $oneEmail->email;

                    $dataRecord[$key]['emailToRetain'] = $toRetain;
                    $dataRecord[$key]['emailToDestroy'] = $toDestroy;
                    $dataRecord[$key]['idToRetain'] = $toRetainId;
                    $dataRecord[$key]['idToDestroy'] = $toDestroyId;
                    /**
                     * Swap the id's
                     */
                    DB::transaction(function () use ($key, $toDestroyId, $toRetainId, $duplicateUsers) {
                        $dataRecord[$key]['user_team'] = DB::table('user_team')->where('user_id', $toDestroyId)
                        ->update(['user_id' => $toRetainId]);
                        
                        $dataRecord[$key]['user_project'] = DB::table('user_project')->where('user_id', $toDestroyId)
                        ->update(['user_id' => $toRetainId]);
                        
                        $dataRecord[$key]['user_logins'] = DB::table('user_logins')->where('user_id', $toDestroyId)
                        ->update(['user_id' => $toRetainId]);
                        
                        $dataRecord[$key]['user_group'] = DB::table('user_group')->where('user_id', $toDestroyId)
                        ->update(['user_id' => $toRetainId]);
                        
                        $dataRecord[$key]['user_favorite_project'] = DB::table('user_favorite_project')->where('user_id', $toDestroyId)
                        ->update(['user_id' => $toRetainId]);
                        
                        $dataRecord[$key]['teams'] = DB::table('teams')->where('original_user', $toDestroyId)
                        ->update(['original_user' => $toRetainId]);
                        
                        $dataRecord[$key]['team_user_roles'] = DB::table('team_user_roles')->where('user_id', $toDestroyId)
                        ->update(['user_id' => $toRetainId]);
                        
                        $dataRecord[$key]['team_project_user'] = DB::table('team_project_user')->where('user_id', $toDestroyId)
                        ->update(['user_id' => $toRetainId]);
                        
                        $dataRecord[$key]['sso_logins'] = DB::table('sso_logins')->where('user_id', $toDestroyId)
                        ->update(['user_id' => $toRetainId]);
                        
                        $dataRecord[$key]['projects'] = DB::table('projects')->where('original_user', $toDestroyId)
                        ->update(['original_user' => $toRetainId]);
                        
                        $dataRecord[$key]['password_resets'] = DB::table('password_resets')->where('email', $duplicateUsers[1]['email'])
                        ->update(['email' => $duplicateUsers[0]['email']]);
                        
                        $dataRecord[$key]['organization_user_permissions'] = DB::table('organization_user_permissions')->where('user_id', $toDestroyId)
                        ->update(['user_id' => $toRetainId]);
                        
                        $dataRecord[$key]['notifications'] = DB::table('notifications')->where('notifiable_id', $toDestroyId)->where('notifiable_type', "App\User")
                        ->update(['notifiable_id' => $toRetainId]);
                        
                        $dataRecord[$key]['mobile_app_h5p_results'] = DB::table('mobile_app_h5p_results')->where('email', $duplicateUsers[1]['email'])
                        ->update(['email' => $duplicateUsers[0]['email']]);
                        
                        $dataRecord[$key]['lti_tool_settings'] = DB::table('lti_tool_settings')->where('user_id', $toDestroyId)
                        ->update(['user_id' => $toRetainId]);
                        
                        $dataRecord[$key]['lrs_statements_data'] = DB::table('lrs_statements_data')->where('publisher_id', $toDestroyId)
                        ->update(['publisher_id' => $toRetainId]);
                        
                        $dataRecord[$key]['lms_settings'] = DB::table('lms_settings')->where('user_id', $toDestroyId)->where('lms_login_id', $duplicateUsers[1]['email'])
                        ->update(['user_id' => $toRetainId, 'lms_login_id' => $duplicateUsers[0]['email']]);
                        
                        $dataRecord[$key]['invited_team_users'] = DB::table('invited_team_users')->where('invited_email', $toDestroyId)
                        ->update(['invited_email' => $duplicateUsers[0]['email']]);
                        
                        $dataRecord[$key]['invited_organization_users'] = DB::table('invited_organization_users')->where('invited_email', $toDestroyId)
                        ->update(['invited_email' => $duplicateUsers[0]['email']]);
                        
                        $dataRecord[$key]['invited_group_users'] = DB::table('invited_group_users')->where('invited_email', $toDestroyId)
                        ->update(['invited_email' => $duplicateUsers[0]['email']]);
                        
                        $dataRecord[$key]['h5p_results'] = DB::table('h5p_results')->where('user_id', $toDestroyId)
                        ->update(['user_id' => $toRetainId]);
                        
                        $dataRecord[$key]['h5p_events'] = DB::table('h5p_events')->where('user_id', $toDestroyId)
                        ->update(['user_id' => $toRetainId]);
                        
                        $dataRecord[$key]['h5p_event_logs'] = DB::table('h5p_event_logs')->where('user_id', $toDestroyId)
                        ->update(['user_id' => $toRetainId]);
                        
                        $dataRecord[$key]['h5p_contents_user_data_go'] = DB::table('h5p_contents_user_data_go')->where('user_id', $toDestroyId)
                        ->update(['user_id' => $toRetainId]);
                        
                        $dataRecord[$key]['h5p_contents_user_data'] = DB::table('h5p_contents_user_data')->where('user_id', $toDestroyId)
                        ->update(['user_id' => $toRetainId]);
                        
                        $dataRecord[$key]['h5p_contents'] = DB::table('h5p_contents')->where('user_id', $toDestroyId)
                        ->update(['user_id' => $toRetainId]);
                        
                        $dataRecord[$key]['groups'] = DB::table('groups')->where('original_user', $toDestroyId)
                        ->update(['original_user' => $toRetainId]);
                        
                        $dataRecord[$key]['group_project_user'] = DB::table('group_project_user')->where('user_id', $toDestroyId)
                        ->update(['user_id' => $toRetainId]);
                        
                        $dataRecord[$key]['gclass_api_data'] = DB::table('gclass_api_data')->where('user_id', $toDestroyId)
                        ->update(['user_id' => $toRetainId]);
                        
                        $dataRecord[$key]['gclass_api_data'] = DB::table('gclass_api_data')->where('curriki_teacher_email', $toDestroyId)
                        ->update(['curriki_teacher_email' => $duplicateUsers[0]['email']]);
                        
                        $dataRecord[$key]['brightcove_api_settings'] = DB::table('brightcove_api_settings')->where('user_id', $toDestroyId)->where('account_email', $toDestroyId)
                        ->update(['user_id' => $toRetainId, 'account_email' => $duplicateUsers[0]['email']]);
                        
                        $dataRecord[$key]['activities'] = DB::table('activities')->where('user_id', $toDestroyId)
                        ->update(['user_id' => $toRetainId]);

                        if (DB::table('organization_user_roles')->where('user_id', $toRetainId)) {
                            $dataRecord[$key]['organization_user_roles'] = DB::table('organization_user_roles')->where('user_id', $toDestroyId)
                            ->delete();
                        } else {
                            $dataRecord[$key]['organization_user_roles'] = DB::table('organization_user_roles')->where('user_id', $toDestroyId)
                            ->update(['user_id' => $toRetainId]);
                        }


                        $storeResponnse = Storage::put('Duplicate-users-fix.json', json_encode($dataRecord));

                        $deleteUppercaseUser = $this->model->where('id', $toDestroyId)
                        ->delete();
                    });
                }
            }
        }
        if ($storeResponnse)
        {
            return true;
        }
        return false;
    }

    /**
     * Process the request to export users and their projects and independent activities
     *
     * @param $authUser
     * @param string $path
     * @param $suborganization
     * @param string $methodSource
     * @throws GeneralException
     */
    public function processExportUsersRequest($authUser, $path, $suborganization, $methodSource = "API")
    {
        try {
            $sourceFile = storage_path("app/public/" . (str_replace('/storage/', '', $path)));

            if ($methodSource === "command") {
                $sourceFile = $path;
            }

            $userEmails = [];
            if (file_exists($sourceFile)) {
                $file = fopen($sourceFile, 'r');
                while (($line = fgetcsv($file)) !== FALSE) {
                    if ($line[0] !== '') {
                        $userEmails[] = $line[0];
                    }
                }
                fclose($file);
            } else {
                $return_res = [
                    "success" => false,
                    "message" => "Unable to process export users request."
                ];

                return json_encode($return_res);
            }

            $userObjs = $suborganization->users()->whereIn('email', $userEmails)->get();

            return DB::transaction(function () use ($authUser, $userObjs, $suborganization, $sourceFile, $methodSource) {
                $exportRequestObj = $authUser->exportRequests()->create([
                    'organization_id' => $suborganization->id,
                    'type' => 'USER'
                ]);

                $exportRequestJson = [
                    'id' => $exportRequestObj->id,
                    'organization_id' => $exportRequestObj->organization_id,
                    'user_id' => $exportRequestObj->user_id,
                    'type' => $exportRequestObj->type,
                    'created_at' => $exportRequestObj->created_at,
                    'updated_at' => $exportRequestObj->updated_at
                ];

                $exportRequestsItemsJson = [];

                foreach ($userObjs as $userObj) {
                    $exportRequestsItemObj = $exportRequestObj->exportRequestsItems()->create([
                        'item_id' => $userObj->id,
                        'item_type' => 'USER'
                    ]);

                    $exportRequestsItemJson = [
                        'id' => $exportRequestsItemObj->id,
                        'export_request_id' => $exportRequestsItemObj->export_request_id,
                        'item_id' => $exportRequestsItemObj->item_id,
                        'item_type' => $exportRequestsItemObj->item_type,
                        'created_at' => $exportRequestsItemObj->created_at,
                        'updated_at' => $exportRequestsItemObj->updated_at,
                        'user' => new UserExportResource($userObj)
                    ];

                    $exportRequestsSubItemsJson = [];

                    $userOrgProjectsObjs = $userObj->projects()
                                                ->where('organization_id', $suborganization->id)
                                                ->get();

                    foreach ($userOrgProjectsObjs as $userProjectObj) {
                        try {
                            $projectExportFile = basename($this->projectRepository->exportProject($authUser, $userProjectObj, 'export-requests/projects-'));
                            $itemStatus = 'COMPLETED';
                        } catch (\Throwable $t) {
                            Log::error($t);
                            $itemStatus = 'FAILED';
                            $projectExportFile = 'FAILED EXPORT: ' .$t->getMessage();
                        }

                        $subExportRequestsItemObj = $exportRequestsItemObj->subExportRequestsItems()->create([
                            'item_id' => $userProjectObj->id,
                            'item_type' => 'PROJECT',
                            'item_status' => $itemStatus,
                            'exported_file_path' => $projectExportFile
                        ]);

                        $exportRequestsSubItemsJson[] = [
                            'id' => $subExportRequestsItemObj->id,
                            'parent_id' => $subExportRequestsItemObj->parent_id,
                            'item_id' => $subExportRequestsItemObj->item_id,
                            'item_type' => $subExportRequestsItemObj->item_type,
                            'item_status' => $subExportRequestsItemObj->item_status,
                            'exported_file_path' => $subExportRequestsItemObj->exported_file_path,
                            'created_at' => $subExportRequestsItemObj->created_at,
                            'updated_at' => $subExportRequestsItemObj->updated_at
                        ];
                    }

                    $userIndependentActivitiesObjs = $userObj->independentActivities()
                                                ->has('h5p_content')
                                                ->where('organization_id', $suborganization->id)
                                                ->get();

                    foreach ($userIndependentActivitiesObjs as $independentActivityObj) {
                        try {
                            $independentActivityExportFile = basename($this->independentActivityRepository->exportIndependentActivity($authUser, $independentActivityObj, 'export-requests/independent_activity-'));
                            $itemStatus = 'COMPLETED';
                        } catch (\Throwable $t) {
                            Log::error($t);
                            $itemStatus = 'FAILED';
                            $independentActivityExportFile = 'FAILED EXPORT: ' .$t->getMessage();
                        }

                        $subExportRequestsItemObj = $exportRequestsItemObj->subExportRequestsItems()->create([
                            'item_id' => $independentActivityObj->id,
                            'item_type' => 'INDEPENDENT-ACTIVITY',
                            'item_status' => $itemStatus,
                            'exported_file_path' => $independentActivityExportFile
                        ]);

                        $exportRequestsSubItemsJson[] = [
                            'id' => $subExportRequestsItemObj->id,
                            'parent_id' => $subExportRequestsItemObj->parent_id,
                            'item_id' => $subExportRequestsItemObj->item_id,
                            'item_type' => $subExportRequestsItemObj->item_type,
                            'item_status' => $subExportRequestsItemObj->item_status,
                            'exported_file_path' => $subExportRequestsItemObj->exported_file_path,
                            'created_at' => $subExportRequestsItemObj->created_at,
                            'updated_at' => $subExportRequestsItemObj->updated_at
                        ];
                    }

                    $exportRequestsItemJson['export_request_sub_items'] = $exportRequestsSubItemsJson;
                    $exportRequestsItemsJson[] = $exportRequestsItemJson;
                }

                $exportRequestJson['export_request_items'] = $exportRequestsItemsJson;

                $exportRequestJsonFile = '/exports/export-requests/export_request.json';

                Storage::disk('public')->put($exportRequestJsonFile, json_encode($exportRequestJson));

                if (file_exists($sourceFile)) {
                    if ($methodSource !== "command") {
                        unlink($sourceFile); // Deleted the storage csv file
                    } else {
                        $return_res = [
                            "success" => true,
                            "message" => "Processed export users request successfully",
                            "export_request_id" => $exportRequestObj->id
                        ];
                        return json_encode($return_res);
                    }

                    return [
                        'export_request_id' => $exportRequestObj->id
                    ];
                }
            });
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            \Sentry\captureException($e);
            if ($methodSource === "command") {
                $return_res = [
                    "success" => false,
                    "message" => "Unable to process export users request, please try again later!"
                ];
                return (json_encode($return_res));
            }

            throw new GeneralException('Unable to process export users request, please try again later!');
        }
    }

    /**
     * Process the request to import users and their projects and independent activities
     *
     * @param $suborganization
     * @param string $methodSource
     * @throws GeneralException
     */
    public function processImportUsersRequest($suborganization, $methodSource = "API")
    {
        try {
            $exportRequestJsonFile = 'app/public/exports/export-requests/export_request.json';
            if (file_exists(storage_path($exportRequestJsonFile))) {
                $exportRequestJson = file_get_contents(storage_path($exportRequestJsonFile));

                $exportRequestObj = json_decode($exportRequestJson);

                return DB::transaction(function () use ($exportRequestObj, $suborganization, $methodSource) {

                    if ($exportRequestObj->type == 'USER') {
                        $exportRequestUsers = $exportRequestObj->export_request_items;
                        $excludeFromUserFields = [
                            'id',
                            'organization_role'
                        ];

                        foreach ($exportRequestUsers as $exportRequestUser) {
                            $importedRequestUser = $this->model
                                                        ->where('email', $exportRequestUser->user->email)
                                                        ->first();

                            if (!$importedRequestUser) {
                                $importUser = Arr::except((array) $exportRequestUser->user, $excludeFromUserFields);
                                $importUser['remember_token'] = Str::random(64);
                                $importUser['email_verified_at'] = now();

                                $importedRequestUser = $this->model->create($importUser);
                            }

                            if (!$suborganization->users()->where('user_id', $importedRequestUser->id)->exists()) {
                                $importOrganizationRoleType = $suborganization
                                                        ->roles()
                                                        ->where('name', $exportRequestUser->user->organization_role->name)
                                                        ->first();

                                if (!$importOrganizationRoleType) {
                                    $importOrganizationRoleType = $suborganization->roles()->create([
                                        'name' => $exportRequestUser->user->organization_role->name,
                                        'display_name' => $exportRequestUser->user->organization_role->display_name,
                                    ]);

                                    // $exportPermissionCount = $exportRequestUser->user->organization_role->permissions->count();
                                    $exportPermissionNames = $exportRequestUser->user->organization_role->permissions->pluck('name')->toArray();
                                    $importPermissionsIds = $suborganization
                                                            ->roles()
                                                            ->permissions()
                                                            ->whereIn('name', $exportPermissionNames)
                                                            ->pluck('id')
                                                            ->toArray();

                                    // $importPermissionCount = $importPermissionsIds->count();
                                    // if ($exportPermissionCount !== $importPermissionCount) {
                                    // }

                                    $uiModulePermissionIds = $this->uiOrganizationPermissionMappingRepository
                                    ->getUiModulePermissionIds($importPermissionsIds);

                                    $importOrganizationRoleType->uiModulePermissions()->sync($uiModulePermissionIds);
                                    $importOrganizationRoleType->permissions()->sync($importPermissionsIds);
                                }

                                $suborganization->users()->attach($importedRequestUser->id, ['organization_role_type_id' => $importOrganizationRoleType->id]);
                            }

                            $exportRequestSubItems = $exportRequestUser->export_request_sub_items;

                            foreach ($exportRequestSubItems as $exportRequestSubItem) {
                                if ($exportRequestSubItem->item_status === 'COMPLETED') {
                                    $exportedFilePath = storage_path("app/public/exports/export-requests/" . $exportRequestSubItem->exported_file_path);
                                    if ($exportRequestSubItem->item_type === 'PROJECT') {
                                        $response = $this->projectRepository->importProject($importedRequestUser, $exportedFilePath, $suborganization->id, 'command');
                                        $encodedResponse = json_decode($response,true);

                                        if (config('constants.map-device-lti-client-id')) {
                                            // map-device-lti-client-id
                                            $lms_settings = $this->lmsSettingRepository->findByField('lti_client_id', config('constants.map-device-lti-client-id'));

                                            if (!empty($lms_settings) && isset($encodedResponse['project_id']) && !empty($encodedResponse['project_id'])) {
                                                // Code for Moodle Import
                                                $playlists = $this->playlistRepository->getPlaylistsByProjectId($encodedResponse['project_id']);

                                                foreach ($playlists as $playlist) {
                                                    // Publish playlist into moodle
                                                    $lmsSetting = $this->lmsSettingRepository->find($lms_settings->id);
                                                    $counter = 0;
                                                    $playlistMoodleObj = new PlaylistMoodle($lmsSetting);
                                                    $responseReq = $playlistMoodleObj->send($playlist, ['counter' => intval($counter)]);
                                                }
                                            }
                                        }
                                    } elseif ($exportRequestSubItem->item_type === 'INDEPENDENT-ACTIVITY') {
                                        $response = $this->independentActivityRepository->importIndependentActivity($importedRequestUser, $exportedFilePath, $suborganization->id, 'command');
                                    }
                                }
                            }
                        }
                    }

                    $return_res = [
                        "success" => true,
                        "message" => "Processed import users request successfully",
                        "export_request_id" => $exportRequestObj->id
                    ];

                    return json_encode($return_res);
                });
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            \Sentry\captureException($e);
            if ($methodSource === "command") {
                $return_res = [
                    "success" => false,
                    "message" => "Unable to process import users request, please try again later!"
                ];
                return (json_encode($return_res));
            }

            throw new GeneralException('Unable to process import users request, please try again later!');
        }
    }
}
