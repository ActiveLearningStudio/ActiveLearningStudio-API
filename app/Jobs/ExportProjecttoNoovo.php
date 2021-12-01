<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Models\Project;
use App\Models\Team;
use App\Models\Organization;
use App\User;
use App\Services\NoovoCMSService;
use Illuminate\Support\Facades\Storage;

class ExportProjecttoNoovo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */


     /**
     * @var object
     */
    protected $user;

    /**
     * @var string
     */
    protected $projects;

    /**
     * @var object
     */
    
    protected $noovoCMSService;

    /**
     * @var string
     */
    protected $team;

     /**
     * @var string
     */
    protected $suborganization;
    
    public function __construct(User $user, $projects, $noovoCMSService, Team $team, Organization $suborganization)
    {
       
        $this->user = $user;
        $this->projects = $projects;
        $this->noovoCMSService = $noovoCMSService;
        $this->team = $team;
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
                $upload_file_ids = [];

                $post = [];
                $post['target_company'] = array(
                    'company_name' => $this->suborganization->noovo_client_id,
                    'group_name' => $this->team->noovo_group_id
                );
                $asd = [];
                foreach($this->projects as $project) {
                   
                    // Create the zip archive of folder
                    $export_file = $projectRepository->exportProject($this->user, $project);
                    \Log::Info($export_file);
                    $file_info = array(
                        "filename" => $project->name ,
                        "description"=> $project->description,
                        "url"=> url(Storage::url('exports/'.basename($export_file))),
                        "md5sum"=> md5_file($export_file)
                    );
                    // Upload the zip files into Noovo CMS
                    //$upload_file_id = $this->noovoCMSService->uploadFileToNoovo($export_file, $project);
                    //\Log::info($upload_file_id);
                    //array_push($upload_file_ids, $upload_file_id);
                    array_push($asd, $file_info);
                }

                $post['files'] = $asd;
                \Log::info($post);
               return;
                $upload_file_ids = $this->noovoCMSService->uploadMultipleFilestoNoovo($post);
                \Log::info($upload_file_ids);
                
                $list_data = array(
                    "name" => $this->team->name ." Projects",
                    "description" => $this->team->name ." Projects",
                    "files" => $upload_file_ids,
                    "gid" => 298
                );
                // Create the File List.
                $upload_file_id = $this->noovoCMSService->createFileList($list_data);
               
                
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            
        }
    }
}
