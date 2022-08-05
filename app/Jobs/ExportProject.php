<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Models\Project;
use App\Models\Organization;
use App\User;
use App\Notifications\ProjectExportNotification;


class ExportProject implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Project
     */
    protected $project;

    /**
     * @var Organization
     */
    protected $suborganization;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Project $project,Organization $suborganization)
    {
        $this->user = $user;
        $this->project = $project;
        $this->suborganization = $suborganization;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ProjectRepositoryInterface $projectRepository)
    {
        try {
            $export_file = $projectRepository->exportProject($this->user, $this->project);
            $userName = rtrim($this->user->first_name . ' ' . $this->user->last_name, ' ');
           
            $this->user->notify(new ProjectExportNotification($export_file, $userName, $this->project->name, $this->suborganization->id));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}
