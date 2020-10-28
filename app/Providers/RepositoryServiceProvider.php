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
use App\Repositories\H5pElasticsearchField\H5pElasticsearchFieldRepository;
use App\Repositories\H5pElasticsearchField\H5pElasticsearchFieldRepositoryInterface;
use App\Repositories\H5pLibrary\H5pLibraryRepository;
use App\Repositories\H5pLibrary\H5pLibraryRepositoryInterface;
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
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\UserLmsSettings\UserLmsSettingsRepository;
use App\Repositories\UserLmsSettings\UserLmsSettingsRepositoryInterface;
use App\Repositories\UserLogin\UserLoginRepository;
use App\Repositories\UserLogin\UserLoginRepositoryInterface;
use App\Repositories\GcClasswork\GcClassworkRepository;
use App\Repositories\GcClasswork\GcClassworkRepositoryInterface;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(MetricsRepositoryInterface::class, MetricsRepository::class);
        $this->app->bind(UserLoginRepositoryInterface::class, UserLoginRepository::class);
        $this->app->bind(TeamRepositoryInterface::class, TeamRepository::class);
        $this->app->bind(OrganizationTypeRepositoryInterface::class, OrganizationTypeRepository::class);
        $this->app->bind(UserLmsSettingsRepositoryInterface::class, UserLmsSettingsRepository::class);
        $this->app->bind(GcClassworkRepositoryInterface::class, GcClassworkRepository::class);
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
