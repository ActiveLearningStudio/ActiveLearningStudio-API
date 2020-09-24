<?php

namespace App\Providers;

use App\Repositories\ActivityItem\ActivityItemRepository;
use App\Repositories\ActivityItem\ActivityItemRepositoryInterface;
use App\Repositories\ActivityType\ActivityTypeRepository;
use App\Repositories\ActivityType\ActivityTypeRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepositoryInterface;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Activity\ActivityRepositoryInterface;
use App\Repositories\Playlist\PlaylistRepository;
use App\Repositories\Playlist\PlaylistRepositoryInterface;
use App\Repositories\Project\ProjectRepository;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepository;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\H5pElasticsearchField\H5pElasticsearchFieldRepository;
use App\Repositories\H5pElasticsearchField\H5pElasticsearchFieldRepositoryInterface;
use App\Repositories\H5pLibrary\H5pLibraryRepository;
use App\Repositories\H5pLibrary\H5pLibraryRepositoryInterface;
use App\Repositories\Metrics\MetricsRepositoryInterface;
use App\Repositories\Metrics\MetricsRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Organisation\OrganisationRepository;
use App\Repositories\Organisation\OrganisationRepositoryInterface;

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
        $this->app->bind(OrganisationRepositoryInterface::class, OrganisationRepository::class);
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
