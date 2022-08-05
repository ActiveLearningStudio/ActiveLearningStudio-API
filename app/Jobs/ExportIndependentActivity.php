<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\IndependentActivity\IndependentActivityRepositoryInterface;
use App\Models\IndependentActivity;
use App\User;
use App\Models\Organization;
use App\Notifications\ActivityExportNotification;


class ExportIndependentActivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var independent_activity
     */
    protected $independent_activity;

    /**
     * @var Organization
     */
    protected $suborganization;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, IndependentActivity $independent_activity, Organization $suborganization)
    {
        $this->user = $user;
        $this->independent_activity = $independent_activity;
        $this->suborganization = $suborganization;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(IndependentActivityRepositoryInterface $independentActivityRepository)
    {
        try {
            $export_file = $independentActivityRepository->exportIndependentActivity($this->user, $this->independent_activity);
            $userName = rtrim($this->user->first_name . ' ' . $this->user->last_name, ' ');
           \Log::info($export_file);
            $this->user->notify(new ActivityExportNotification($export_file, $userName, $this->independent_activity->title, $this->suborganization->id));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}
