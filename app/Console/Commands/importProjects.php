<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\User;

class importProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:project {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To import a project into mapped device';

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
    public function handle(ProjectRepositoryInterface $projectRepository)
    {
        $path = $this->argument('path');
        $ext = pathinfo(basename($path), PATHINFO_EXTENSION);
        $mime_type = mime_content_type($path);
        if ($mime_type !== "application/zip") {
            $return_arr = [
                            "success"=> false,
                            "message" => "Please provide a valid zip file"
                        ];
            $this->error(json_encode($return_arr));
            return;
        }
                
        $user = User::find(config('import-mapped-device.user_id'));
        
        $response = $projectRepository->importProject($user, $path, config('import-mapped-device.organization_id'), "command");
        $this->info($response);
    }
}
