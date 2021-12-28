<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteExportedProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:exportedProjects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $projectDir = storage_path('app/public/exports/');
        $files = scandir($projectDir);
        
        $currentDate = date('Y-m-d');
        
        $limitDays = "- ". config('constants.default-exported-projects-days-limit');
        
        $oldDate = date('Y-m-d', strtotime($limitDays . "days", strtotime($currentDate)));
        
        foreach ($files as $file) {
            if ($file == "." || $file == "..") {
                continue;
            };

            $path = 'public/exports/' . $file;
            
            $time = Storage::lastModified($path);
            
            if ($time < strtotime($oldDate)) {
               
                if (is_dir( $projectDir . $file)) {
                    \Log:: info($path . " has been removed");
                    Storage::deleteDirectory($path); continue;
                }
                
                Storage::delete($path);
                \Log:: info($path . " has been removed");
            }
        }
    }
}
