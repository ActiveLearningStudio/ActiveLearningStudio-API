<?php

namespace App\Providers;

use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Activity\ActivityRepositoryInterface;
use App\Repositories\ActivityItem\ActivityItemRepository;
use App\Repositories\ActivityItem\ActivityItemRepositoryInterface;
use App\Repositories\ActivityType\ActivityTypeRepository;
use App\Repositories\ActivityType\ActivityTypeRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepository;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use App\Repositories\EloquentRepositoryInterface;
use App\Repositories\GoogleClassroom\GoogleClassroomRepository;
use App\Repositories\GoogleClassroom\GoogleClassroomRepositoryInterface;
use App\Repositories\H5pElasticsearchField\H5pElasticsearchFieldRepository;
use App\Repositories\H5pElasticsearchField\H5pElasticsearchFieldRepositoryInterface;
use App\Repositories\H5pLibrary\H5pLibraryRepository;
use App\Repositories\H5pLibrary\H5pLibraryRepositoryInterface;
use App\Repositories\H5pContent\H5pContentRepository;
use App\Repositories\H5pContent\H5pContentRepositoryInterface;
use App\Repositories\Metrics\MetricsRepository;
use App\Repositories\Metrics\MetricsRepositoryInterface;
use App\Repositories\OrganizationType\OrganizationTypeRepository;
use App\Repositories\OrganizationType\OrganizationTypeRepositoryInterface;
use App\Repositories\Playlist\PlaylistRepository;
use App\Repositories\Playlist\PlaylistRepositoryInterface;
use App\Repositories\Project\ProjectRepository;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Repositories\Team\TeamRepository;
use App\Repositories\Team\TeamRepositoryInterface;
use App\Repositories\Group\GroupRepository;
use App\Repositories\Group\GroupRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\UserLmsSettings\UserLmsSettingsRepository;
use App\Repositories\UserLmsSettings\UserLmsSettingsRepositoryInterface;
use App\Repositories\UserLogin\UserLoginRepository;
use App\Repositories\UserLogin\UserLoginRepositoryInterface;
use App\Repositories\GcClasswork\GcClassworkRepository;
use App\Repositories\GcClasswork\GcClassworkRepositoryInterface;
use App\Repositories\InvitedTeamUser\InvitedTeamUserRepository;
use App\Repositories\InvitedTeamUser\InvitedTeamUserRepositoryInterface;
use App\Repositories\InvitedGroupUser\InvitedGroupUserRepository;
use App\Repositories\InvitedGroupUser\InvitedGroupUserRepositoryInterface;
use App\Repositories\LRSStatementsData\LRSStatementsDataRepository;
use App\Repositories\LRSStatementsData\LRSStatementsDataRepositoryInterface;
use App\Repositories\CurrikiGo\ContentUserDataGo\ContentUserDataGoRepositoryInterface;
use App\Repositories\CurrikiGo\ContentUserDataGo\ContentUserDataGoRepository;
use App\Repositories\LRSStatementsSummaryData\LRSStatementsSummaryDataRepository;
use App\Repositories\LRSStatementsSummaryData\LRSStatementsSummaryDataRepositoryInterface;
use App\Repositories\CurrikiGo\Outcome\OutcomeRepository;
use App\Repositories\CurrikiGo\Outcome\OutcomeRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Organization\OrganizationRepository;
use App\Repositories\Organization\OrganizationRepositoryInterface;
use App\Repositories\InvitedOrganizationUser\InvitedOrganizationUserRepository;
use App\Repositories\InvitedOrganizationUser\InvitedOrganizationUserRepositoryInterface;
use App\Repositories\OrganizationPermissionType\OrganizationPermissionTypeRepository;
use App\Repositories\OrganizationPermissionType\OrganizationPermissionTypeRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
        $this->app->bind(PlaylistRepositoryInterface::class, PlaylistRepository::class);
        $this->app->bind(ActivityRepositoryInterface::class, ActivityRepository::class);
        $this->app->bind(ActivityTypeRepositoryInterface::class, ActivityTypeRepository::class);
        $this->app->bind(ActivityItemRepositoryInterface::class, ActivityItemRepository::class);
        $this->app->bind(LmsSettingRepositoryInterface::class, LmsSettingRepository::class);
        $this->app->bind(H5pElasticsearchFieldRepositoryInterface::class, H5pElasticsearchFieldRepository::class);
        $this->app->bind(H5pLibraryRepositoryInterface::class, H5pLibraryRepository::class);
        $this->app->bind(H5pContentRepositoryInterface::class, H5pContentRepository::class);
        $this->app->bind(MetricsRepositoryInterface::class, MetricsRepository::class);
        $this->app->bind(UserLoginRepositoryInterface::class, UserLoginRepository::class);
        $this->app->bind(TeamRepositoryInterface::class, TeamRepository::class);
        $this->app->bind(GroupRepositoryInterface::class, GroupRepository::class);
        $this->app->bind(OrganizationTypeRepositoryInterface::class, OrganizationTypeRepository::class);
        $this->app->bind(UserLmsSettingsRepositoryInterface::class, UserLmsSettingsRepository::class);
        $this->app->bind(GcClassworkRepositoryInterface::class, GcClassworkRepository::class);
        $this->app->bind(InvitedTeamUserRepositoryInterface::class, InvitedTeamUserRepository::class);
        $this->app->bind(InvitedGroupUserRepositoryInterface::class, InvitedGroupUserRepository::class);
        $this->app->bind(LRSStatementsDataRepositoryInterface::class, LRSStatementsDataRepository::class);
        $this->app->bind(ContentUserDataGoRepositoryInterface::class, ContentUserDataGoRepository::class);
        $this->app->bind(LRSStatementsSummaryDataRepositoryInterface::class, LRSStatementsSummaryDataRepository::class);
        $this->app->bind(OrganizationRepositoryInterface::class, OrganizationRepository::class);
        $this->app->bind(InvitedOrganizationUserRepositoryInterface::class, InvitedOrganizationUserRepository::class);
        $this->app->bind(OrganizationPermissionTypeRepositoryInterface::class, OrganizationPermissionTypeRepository::class);
        $this->app->bind(OutcomeRepositoryInterface::class, OutcomeRepository::class);
        $this->app->bind(GoogleClassroomRepositoryInterface::class, GoogleClassroomRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
