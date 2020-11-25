<?php

namespace App\Console\Commands;

use Aws\S3\S3Client;
use Aws\S3\Transfer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CopyH5PContentDir extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'copy:h5p';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is one time script to copy the H5P Content directory activities to MINIO S3 bucket.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Using transfer manager for moving the content directory
     * Much faster than laravel filesystem and less memory usage
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Script Started: Copying the H5P content directory to S3 Bucket. Start Time: ' . now());
        $client = new S3Client([
            'credentials' => [
                'key' => config('filesystems.disks.minio.key'),
                'secret' => config('filesystems.disks.minio.secret')
            ],
            'region' => config('filesystems.disks.minio.region'),
            'version' => 'latest',
            'use_path_style_endpoint' => true,
            'endpoint' => config('filesystems.disks.minio.endpoint'),
        ]);

        $path = storage_path('app/public/h5p/content/'); // H5P Content directory path
        $dest = 's3://' . config('filesystems.disks.minio.bucket') . '/content'; // upload path of S3 bucket

        // Create a transfer object.
        $manager = new Transfer($client, $path, $dest);

        // Perform the transfer synchronously.
        $manager->transfer();
        Log::info('H5P copy content directory script finished successfully! End Time: ' . now());
    }
}
