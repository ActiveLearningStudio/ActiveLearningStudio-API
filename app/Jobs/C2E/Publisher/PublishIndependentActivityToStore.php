<?php

namespace App\Jobs\C2E\Publisher;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\C2E\Publisher\PublisherRepositoryInterface;
use App\Models\IndependentActivity;
use App\User;
use App\Models\Organization;
use App\Models\C2E\Publisher\Publisher;
use App\Notifications\C2E\Publisher\ActivityPublishNotification;


class PublishIndependentActivityToStore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Publisher
     */
    protected $publisher;
    
    /**
     * @var IndependentActivity
     */
    protected $independentActivity;

    /**
     * @var
     */
    protected $storeId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Publisher $publisher, IndependentActivity $independentActivity, $storeId)
    {
        $this->user = $user;
        $this->publisher = $publisher;
        $this->independentActivity = $independentActivity;
        $this->storeId = $storeId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(PublisherRepositoryInterface $publisherRepository)
    {
        try {
            $response = $publisherRepository
                            ->publishIndependentActivity(
                                $this->user,
                                $this->publisher,
                                $this->independentActivity,
                                $this->storeId
                            );
            $userName = rtrim($this->user->first_name . ' ' . $this->user->last_name, ' ');
           \Log::info($response);
            $this->user->notify(new ActivityPublishNotification($userName, $this->independentActivity->title, $this->independentActivity->organization_id));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}
