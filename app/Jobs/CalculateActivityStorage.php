<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Activity;
use App\Models\ActivityMetric;

class CalculateActivityStorage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $activity;
    public static $monitor = false; // this job should not be monitored

    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    public function handle()
    {
        // All uploaded content files by users of H5P are stored in this path where the final directory
        // is the h5p_content_id

        $path = storage_path('app/public/h5p/content/'.$this->activity->h5p_content_id);
        $bytes = $this->GetDirectorySize($path);

        $metrics = ActivityMetric::firstOrNew(
            ['activity_id' => $this->activity->id],
            [
                'view_count' => 0,
                'share_count' => 0,
                'used_storage' => 0,
                'used_bandwidth' => 0,
            ]
        );
        $metrics->used_storage = $bytes;
        $metrics->save();
    }

    private function GetDirectorySize($path){
        $bytestotal = 0;
        $path = realpath($path);
        if($path!==false && $path!='' && file_exists($path)){
            foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS)) as $object){
                $bytestotal += $object->getSize();
            }
        }
        return $bytestotal;
    }
}
