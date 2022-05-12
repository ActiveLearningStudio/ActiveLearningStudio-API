<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\IndependentActivity\IndependentActivityRepositoryInterface;
use App\User;
use App\Notifications\ActivityImportNotification;

class ImportIndependentActivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

     /**
     * @var User
     */
    protected $user;

    /**
     * @var 
     */
    protected $path;
    
    /**
     * @var
     */
    protected $organization_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $path, $organization_id = null)
    {
        $this->user = $user;
        $this->path = $path;
        $this->organization_id = $organization_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(IndependentActivityRepositoryInterface $independentActivityRepository)
    {
        try {
            $activityTitle = $independentActivityRepository->importIndependentActivity($this->user, $this->path, $this->organization_id);
            $userName = rtrim($this->user->first_name . ' ' . $this->user->last_name, ' ');
            $this->user->notify(new ActivityImportNotification($userName, $activityTitle));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}
