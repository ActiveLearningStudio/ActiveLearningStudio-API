<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', 'Auth\AuthController@register')->name('register');
Route::post('login', 'Auth\AuthController@login')->name('login');
Route::post('admin/login', 'Auth\AuthController@adminLogin')->name('admin.login');
Route::post('login/google', 'Auth\AuthController@loginWithGoogle');
Route::post('login/sso', 'Auth\AuthController@ssoLogin');
Route::get('oauth/{provider}/redirect', 'Auth\AuthController@oauthRedirect');
Route::get('oauth/{provider}/callback', 'Auth\AuthController@oauthCallBack');
Route::post('forgot-password', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('reset-password', 'Auth\ResetPasswordController@resetPass');
Route::post('verify-email', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('verify-email/resend', 'Auth\VerificationController@resendEmail')->name('verification.resend');
Route::post('logout', 'Auth\AuthController@logout')->name('logout')->middleware(['auth:api', 'verified']);

Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1'], function () {
    Route::get('projects/{project}/load-shared', 'ProjectController@loadShared');
    Route::get('playlists/{playlist}/load-shared', 'PlaylistController@loadShared');
    Route::get('playlists/update-order', 'PlaylistController@populateOrderNumber');

    Route::get('activities/{activity}/log-view', 'MetricsController@activityLogView')->name('metrics.activity-log');
    Route::get('playlists/{playlist}/log-view', 'MetricsController@playlistLogView')->name('metrics.playlist-log');
    Route::get('projects/{project}/log-view', 'MetricsController@projectLogView')->name('metrics.project-log');

    Route::get('organization-types', 'OrganizationTypesController@index');

    Route::get('organization/get-by-domain', 'OrganizationController@getByDomain')->name('organization.get-by-domain');

    Route::get('search/public', 'SearchController@public');

    Route::middleware(['auth:api', 'verified'])->group(function () {
        Route::get('users/organizations', 'UserController@getOrganizations');
        Route::post('subscribe', 'UserController@subscribe');
        Route::get('users/me', 'UserController@me');
        Route::get('users/notifications', 'UserController@listNotifications');
        Route::get('users/notifications/read-all', 'UserController@readAllNotification');
        Route::post('users/notifications/{notification}/read', 'UserController@readNotification');
        Route::post('users/notifications/{notification}/delete', 'UserController@deleteNotification');
        Route::post('users/search', 'UserController@getAllUsers');
        Route::post('suborganization/{suborganization}/users/search', 'UserController@getOrgUsers');
        Route::post('suborganization/{suborganization}/users/check', 'UserController@checkOrgUser');
        Route::post('users/update-password', 'UserController@updatePassword');
        Route::get('users/me/redeem/{offerName}', 'UserMembershipController@redeemOffer')->name('membership.redeem-offer');
        Route::apiResource('users', 'UserController')->only([
            'index', 'show', 'update', 'destroy'
        ]);

        // Teams
        Route::post('teams/invite', 'TeamController@inviteTeamMember');
        Route::post('teams/{team}/invite-member', 'TeamController@inviteMember');
        Route::post('suborganization/{suborganization}/teams/{team}/invite-members', 'TeamController@inviteMembers');
        Route::post('teams/{team}/remove', 'TeamController@removeMember');
        Route::post('teams/{team}/add-projects', 'TeamController@addProjects');
        Route::post('teams/{team}/remove-project', 'TeamController@removeProject');
        Route::post('teams/{team}/projects/{project}/add-members', 'TeamController@addMembersToProject');
        Route::post('teams/{team}/projects/{project}/remove-member', 'TeamController@removeMemberFromProject');
        Route::get('suborganization/{suborganization}/get-teams', 'TeamController@getOrgTeams');
        Route::get('suborganization/{suborganization}/team-role-types', 'TeamController@teamRoleTypes');
        Route::get('suborganization/{suborganization}/team/{team}/team-permissions', 'TeamController@getUserTeamPermissions');
        Route::put('suborganization/{suborganization}/team/{team}/update-team-member-role', 'TeamController@updateTeamMemberRole');
        Route::apiResource('suborganization.teams', 'TeamController');

        // Groups
        Route::post('groups/invite', 'GroupController@inviteGroupMember');
        Route::post('groups/{group}/invite-member', 'GroupController@inviteMember');
        Route::post('suborganization/{suborganization}/groups/{group}/invite-members', 'GroupController@inviteMembers');
        Route::post('groups/{group}/remove', 'GroupController@removeMember');
        Route::post('groups/{group}/add-projects', 'GroupController@addProjects');
        Route::post('groups/{group}/remove-project', 'GroupController@removeProject');
        Route::post('groups/{group}/projects/{project}/add-members', 'GroupController@addMembersToProject');
        Route::post('groups/{group}/projects/{project}/remove-member', 'GroupController@removeMemberFromProject');
        Route::get('suborganization/{suborganization}/get-groups', 'GroupController@getOrgGroups');
        Route::apiResource('suborganization.groups', 'GroupController');


        Route::post('suborganization/{suborganization}/projects/upload-thumb', 'ProjectController@uploadThumb');
        Route::get('suborganization/{suborganization}/projects/recent', 'ProjectController@recent');
        Route::get('suborganization/{suborganization}/projects/default', 'ProjectController@default');
        Route::get('suborganization/{suborganization}/projects/detail', 'ProjectController@detail');
        Route::get('projects/update-order', 'ProjectController@populateOrderNumber');
        Route::get('suborganization/{suborganization}/projects/favorites', 'ProjectController@getFavorite');
        Route::post('suborganization/{suborganization}/projects/reorder', 'ProjectController@reorder');
        Route::get('projects/{project}/indexing', 'ProjectController@indexing');
        Route::get('projects/{project}/status-update', 'ProjectController@statusUpdate');
        Route::post('suborganization/{suborganization}/projects/{project}/share', 'ProjectController@share');
        Route::post('suborganization/{suborganization}/projects/{project}/clone', 'ProjectController@clone');
        Route::post('suborganization/{suborganization}/projects/{project}/export', 'ProjectController@exportProject');
        Route::post('suborganization/{suborganization}/projects/import', 'ProjectController@importProject');


        Route::post('suborganization/{suborganization}/projects/{project}/remove-share', 'ProjectController@removeShare');
        Route::post('suborganization/{suborganization}/projects/{project}/favorite', 'ProjectController@favorite');
        Route::get('suborganization/{suborganization}/team-projects', 'ProjectController@getTeamProjects');
        Route::apiResource('suborganization.projects', 'ProjectController');

        Route::post('projects/{project}/playlists/reorder', 'PlaylistController@reorder');
        Route::post('projects/{project}/playlists/{playlist}/clone', 'PlaylistController@clone');
        Route::apiResource('projects.playlists', 'PlaylistController');

        Route::post('playlists/{playlist}/activities/{activity}/clone', 'ActivityController@clone');
        Route::post('activities/upload-thumb', 'ActivityController@uploadThumb');
        Route::get('activities/{activity}/share', 'ActivityController@share');
        Route::get('activities/update-order', 'ActivityController@populateOrderNumber');
        Route::get('activities/{activity}/remove-share', 'ActivityController@removeShare');
        Route::get('activities/{activity}/detail', 'ActivityController@detail');
        Route::get('activities/{activity}/h5p', 'ActivityController@h5p');
        Route::get('activities/{activity}/h5p-resource-settings', 'ActivityController@getH5pResourceSettings');
        Route::get('activities/{activity}/h5p-resource-settings-open', 'ActivityController@getH5pResourceSettingsOpen');
        Route::apiResource('playlists.activities', 'ActivityController');

        Route::get('activity-types/{activityType}/items', 'ActivityTypeController@items');
        Route::apiResource('activity-types', 'ActivityTypeController');

        Route::apiResource('activity-items', 'ActivityItemController');

        Route::get('users/{user}/metrics', 'UserMetricsController@show')->name('metrics.user');
        Route::get('users/{user}/membership', 'UserMembershipController@show')->name('membership.show');

        Route::get('h5p/settings', 'H5pController@create');
        Route::get('h5p/activity/{activity}', 'H5pController@showByActivity');
        Route::apiResource('h5p', 'H5pController');

        Route::group(['prefix' => 'h5p'], function () {
            // H5P Ajax calls
            Route::match(['GET', 'POST'], 'ajax/libraries', '\Djoudi\LaravelH5p\Http\Controllers\AjaxController@libraries')->name('h5p.ajax.libraries');
            Route::get('ajax/single-libraries', '\Djoudi\LaravelH5p\Http\Controllers\AjaxController@singleLibrary')->name('h5p.ajax.single-libraries');
            Route::any('ajax/content-type-cache', '\Djoudi\LaravelH5p\Http\Controllers\AjaxController@contentTypeCache')->name('h5p.ajax.content-type-cache');
            Route::any('ajax/library-install', '\Djoudi\LaravelH5p\Http\Controllers\AjaxController@libraryInstall')->name('h5p.ajax.library-install');
            Route::post('ajax/library-upload', '\Djoudi\LaravelH5p\Http\Controllers\AjaxController@libraryUpload')->name('h5p.ajax.library-upload');
            Route::post('ajax/rebuild-cache', '\Djoudi\LaravelH5p\Http\Controllers\AjaxController@rebuildCache')->name('h5p.ajax.rebuild-cache');
            Route::any('ajax/filter', '\Djoudi\LaravelH5p\Http\Controllers\AjaxController@filter')->name('h5p.ajax.filter');
            Route::any('ajax/finish', '\Djoudi\LaravelH5p\Http\Controllers\AjaxController@finish')->name('h5p.ajax.finish');
            Route::any('ajax/content-user-data', 'H5pController@contentUserData')->name('h5p.ajax.content-user-data');
            Route::any('h5p-result/my', '\Djoudi\LaravelH5p\Http\Controllers\H5PResultController@my')->name("h5p.result.my");
        });

        // Elasticsearch
        Route::get('search', 'SearchController@search');
        Route::get('search/advanced', 'SearchController@advance');
        Route::get('search/dashboard', 'SearchController@dashboard');

        // Suborganizations
        Route::get('suborganizations/{suborganization}/user-has-permission', 'SuborganizationController@userHasPermission')->name('suborganizations.user-has-permission');
        Route::get('suborganizations/{suborganization}/permissions', 'SuborganizationController@getUserPermissions')->name('suborganizations.get-user-permissions');
        Route::get('suborganizations/{suborganization}/default-permissions', 'SuborganizationController@getDefaultPermissions')->name('suborganizations.get-default-permissions');
        Route::post('suborganizations/{suborganization}/add-role', 'SuborganizationController@addRole')->name('suborganizations.add-role');
        Route::put('suborganizations/{suborganization}/update-role', 'SuborganizationController@updateRole')->name('suborganizations.update-role');
        Route::get('suborganizations/visibility-types', 'SuborganizationController@getVisibilityTypes')->name('suborganizations.get-visibility-types');
        Route::get('suborganizations/{suborganization}/roles', 'SuborganizationController@getRoles')->name('suborganizations.get-roles');
        Route::get('suborganizations/{suborganization}/role/{roleId}', 'SuborganizationController@getRoleDetail')->name('suborganizations.get-role-detail');
        Route::post('suborganizations/{suborganization}/upload-thumb', 'SuborganizationController@uploadThumb');
        Route::get('suborganizations/{suborganization}/member-options', 'SuborganizationController@showMemberOptions')->name('suborganizations.member-options');
        Route::get('suborganizations/{suborganization}/users', 'SuborganizationController@getUsers')->name('suborganizations.get-users');
        Route::post('suborganizations/{suborganization}/add-user', 'SuborganizationController@addUser')->name('suborganizations.add-user');
        Route::post('suborganizations/{suborganization}/add-new-user', 'UserController@addNewUser')->name('suborganizations.add-new-user');
        Route::post('suborganizations/{suborganization}/invite-members', 'SuborganizationController@inviteMembers')->name('suborganizations.invite-members');
        Route::put('suborganizations/{suborganization}/update-user', 'SuborganizationController@updateUser')->name('suborganizations.update-user');
        Route::put('suborganizations/{suborganization}/update-user-detail', 'UserController@updateUserDetail')->name('suborganizations.update-user-detail');
        Route::delete('suborganizations/{suborganization}/delete-user', 'SuborganizationController@deleteUser')->name('suborganizations.delete-user');
        Route::delete('suborganizations/{suborganization}/remove-user', 'SuborganizationController@removeUser')->name('suborganizations.remove-user');
        Route::apiResource('suborganizations', 'SuborganizationController')->except([
            'index'
        ]);
        Route::get('suborganizations/{suborganization}/index', 'SuborganizationController@index')->name('suborganizations.index');

        /*********************** NEW ADMIN PANEL ROUTES ************************/
        Route::get('suborganizations/{suborganization}/projects', 'ProjectController@getOrgProjects')->name('suborganizations.get-projects');
        Route::get('projects/{project}/indexes/{index}', 'ProjectController@updateIndex');
        Route::post('projects/starter/{flag}', 'ProjectController@toggleStarter');
        // lms-settings
        Route::apiResource('suborganizations/{suborganization}/lms-settings', 'LmsSettingsController');
        Route::get('users/report/basic', 'UserController@reportBasic')->name('users.report.basic');
        // queue-monitor
        Route::get('queue-monitor/jobs', 'QueueMonitorController@jobs');
        Route::get('queue-monitor/jobs/retry/all', 'QueueMonitorController@retryAll');
        Route::get('queue-monitor/jobs/forget/all', 'QueueMonitorController@forgetAll');
        Route::get('queue-monitor/jobs/retry/{job}', 'QueueMonitorController@retryJob');
        Route::get('queue-monitor/jobs/forget/{job}', 'QueueMonitorController@forgetJob');
        Route::apiResource('queue-monitor', 'QueueMonitorController');
        // activity items
        Route::get('get-activity-items', 'ActivityItemController@getItems');
        Route::post('activity-types/upload-thumb', 'ActivityTypeController@uploadImage');
        Route::post('activity-items/upload-thumb', 'ActivityItemController@uploadImage');
        /*********************** ENDED NEW ADMIN PANEL ROUTES ************************/

        // Permissions
        Route::get('permissions', 'OrganizationPermissionTypeController@index')->name('permissions.index');

        // CurrikiGo
        Route::group(['prefix' => 'go'], function () {
            // LMS Settings
            Route::group(['prefix' => 'lms-settings'], function () {
                Route::get('user/me', 'CurrikiGo\LmsSettingController@my');
            });

            Route::group(['prefix' => 'canvas'], function () {
                Route::post('projects/{project}/playlists/{playlist}/publish', 'CurrikiGo\PublishController@playlistToCanvas');
                Route::post('projects/{project}/fetch', 'CurrikiGo\CourseController@fetchFromCanvas');
            });

            Route::group(['prefix' => 'moodle'], function () {
                Route::post('projects/{project}/playlists/{playlist}/publish', 'CurrikiGo\PublishController@playlistToMoodle');
                Route::post('projects/{project}/fetch', 'CurrikiGo\CourseController@fetchFromMoodle');
            });

            // Specific routes for Safari Montage.
            Route::group(['prefix' => 'safarimontage'], function () {
                Route::post('projects/{project}/playlists/{playlist}/activities/{activity}/publish',
                'CurrikiGo\PublishController@activityToSafariMontage');
            });

            Route::post('{lms}/projects/{project}/playlists/{playlist}/publish', 'CurrikiGo\PublishController@playlistToGeneric');
            Route::post('{lms}/projects/{project}/fetch', 'CurrikiGo\CourseController@fetchFromGeneric');
            Route::post('{lms}/login', 'CurrikiGo\LmsServicesController@login');
        });

        // Google Share
        Route::group(['prefix' => 'google-classroom'], function () {
            Route::post('access-token', 'GoogleClassroomController@saveAccessToken');
            Route::get('courses', 'GoogleClassroomController@getCourses');
            Route::post('projects/{project}/copy', 'GoogleClassroomController@copyProject');
        });

        Route::get('user-lms-settings', 'UserLmsSettingsController@index');
    });
    Route::get('go/getxapifile/{activity}', 'CurrikiGo\LmsServicesController@getXAPIFile');
    // public route for get user's shared projects
    Route::post('projects/shared', 'UserController@sharedProjects');

    Route::get('activities/{activity}/h5p-resource-settings-shared', 'ActivityController@getH5pResourceSettingsShared');
    // H5P Activity public route
    Route::get('h5p/activity/{activity}/visibility/{visibility}', 'H5pController@showByActivity');
    // Route to support H5P Editor's core js library file upload with 'new XMLHttpRequest()'
    Route::any('h5p/ajax/files', '\Djoudi\LaravelH5p\Http\Controllers\AjaxController@files')->name('h5p.ajax.files');
    // H5P export public route for H5P toolbar and cloning
    Route::get('h5p/export/{id}', '\Djoudi\LaravelH5p\Http\Controllers\DownloadController')->name('h5p.export');
    // H5P embed
    Route::get('h5p/embed/{id}', 'H5pController@embed');
    // Public route used for LTI previews
    Route::post('go/lms/projects', 'CurrikiGo\LmsController@projects');
    Route::get('go/lms/project/{project}', 'CurrikiGo\LmsController@project');
    Route::post('go/lms/activities', 'CurrikiGo\LmsController@activities');
    // LTI Playlist
    Route::get('playlists/{playlist}/lti', 'PlaylistController@loadLti');
    // xAPI Statments
    Route::post('xapi/statements', 'XapiController@saveStatement');
    // Google Classroom Student workflow
    Route::group(['prefix' => 'google-classroom'], function () {
        Route::post('turnin/{classwork}', 'GoogleClassroomController@turnIn');
        Route::post('validate-summary-access', 'GoogleClassroomController@validateSummaryPageAccess');
        Route::post('classwork/{classwork}/submission', 'GoogleClassroomController@getStudentSubmission');
        Route::get('activities/{activity}/h5p-resource-settings', 'GoogleClassroomController@getH5pResourceSettings');
        Route::any('h5p/ajax/content-user-data', 'H5pController@contentUserData');
    });
    // Outcome
    Route::post('outcome/summary', 'CurrikiGo\OutcomeController@getStudentResultGroupedSummary');
    // XAPI extract
    Route::get('xapi-extract', 'CurrikiGo\ExtractXAPIJSONController@runJob');

    Route::get('error', 'ErrorController@show')->name('api/error');

    /*********************** ADMIN PANEL ROUTES ************************/
    Route::group([
        'prefix' => 'admin',
        'as' => 'v1.admin.',
        'namespace' => 'Admin',
        'name' => 'admin.',
        'middleware' => ['auth:api', 'verified', 'admin']
    ], function () {
        // users
        Route::get('users/report/basic', 'UserController@reportBasic')->name('users.report.basic');
        Route::post('users/bulk/import', 'UserController@bulkImport')->name('users.bulk.import');
        Route::get('users/assign/starter-projects', 'UserController@assignStarterProjects')->name('users.assign.starter-projects');
        Route::get('users/{user}/roles/{role}', 'UserController@updateRole')->name('users.update.role');
        Route::apiResource('users', 'UserController');

        // projects
        Route::post('projects/indexes', 'ProjectController@updateIndexes');
        Route::get('projects/user-starters/flag', 'ProjectController@updateUserStarterFlag');
        Route::post('projects/starter/{flag}', 'ProjectController@toggleStarter');
        Route::get('projects/{project}/indexes/{index}', 'ProjectController@updateIndex');
        Route::get('projects/{project}/public-status', 'ProjectController@togglePublicStatus');
        Route::get('projects/{project}/load-shared', 'ProjectController@loadShared');
        Route::apiResource('projects', 'ProjectController');

        // lms-settings
        Route::apiResource('lms-settings', 'LmsSettingController');

        // activity-types
        Route::apiResource('activity-types', 'ActivityTypeController');

        // activity-items
        Route::apiResource('activity-items', 'ActivityItemController');

        // organization-types
        Route::apiResource('organization-types', 'OrganizationTypesController');

        // queue-monitor
        Route::get('queue-monitor/jobs', 'QueueMonitorController@jobs');
        Route::get('queue-monitor/jobs/retry/all', 'QueueMonitorController@retryAll');
        Route::get('queue-monitor/jobs/forget/all', 'QueueMonitorController@forgetAll');
        Route::get('queue-monitor/jobs/retry/{job}', 'QueueMonitorController@retryJob');
        Route::get('queue-monitor/jobs/forget/{job}', 'QueueMonitorController@forgetJob');
        Route::apiResource('queue-monitor', 'QueueMonitorController');

        // organizations
        Route::get('organizations/report/basic', 'OrganizationController@reportBasic')->name('organizations.report.basic');
        Route::apiResource('organizations', 'OrganizationController');
        Route::delete('organizations/{organization}/user/{user}', 'OrganizationController@deleteUser')->name('organizations.delete-user');
        Route::get('organizations/{id}/parent-options', 'OrganizationController@showParentOptions')->name('organizations.parent-options');
        Route::get('organizations/{id}/member-options', 'OrganizationController@showMemberOptions')->name('organizations.member-options');
    });

    // admin public routes for downloads / uploads
    Route::get('admin/users/import/sample-file', 'Admin\UserController@downloadSampleFile')->name('users.import.sample-file');
});
