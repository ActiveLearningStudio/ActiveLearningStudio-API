<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Models\Project;
use App\User;
use App\Notifications\ProjectImportNotification;

class ImportProject implements ShouldQueue
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
    public function handle(ProjectRepositoryInterface $projectRepository)
    {
        try {
            $projectName = $projectRepository->importProject($this->user, $this->path, $this->organization_id);
            $userName = rtrim($this->user->first_name . ' ' . $this->user->last_name, ' ');
            $this->user->notify(new ProjectImportNotification($userName, $projectName));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}
