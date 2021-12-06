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
use App\Models\NoovoLogs;

class ExportProjecttoNoovo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
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
                    'group_name' => $this->team->noovo_group_title
                );
                $files_arr = [];
                $project_ids = [];
                foreach ($this->projects as $project) {
                   
                    // Create the zip archive of folder
                    $export_file = $projectRepository->exportProject($this->user, $project);
                    \Log::Info($export_file);
                    $file_info = array(
                        "filename" => $project->name ,
                        "description"=> $project->description,
                        "url"=> url(Storage::url('exports/'.basename($export_file))),
                        "md5sum"=> md5_file($export_file)
                    );
                    array_push($files_arr, $file_info);
                    array_push($project_ids, $project->id);
                }

                $post['files'] = $files_arr;
                \Log::info($post);
                // Uploads files into Noovo CMS
                $upload_file_ids = $this->noovoCMSService->uploadMultipleFilestoNoovo($post);
                \Log::info($upload_file_ids);
                
                $list_data = array(
                    "name" => $this->team->name ." Projects",
                    "description" => $this->team->name ." Projects",
                    "files" => $upload_file_ids,
                    "gid" => $this->team->noovo_group_id
                );
                // Create the File List on Noovo CMS
                $file_list_id = $this->noovoCMSService->createFileList($list_data);

                $group_attachment = array(
                    "group" => $this->team->noovo_group_id,
                    "id" => $file_list_id
                );
                // Attach file list with Group
                $this->setFileListtoGroup->createFileList($group_attachment);

                // Insert Logging
                NoovoLogs::create([
                    'organization_id' => $this->suborganization->id,
                    'team_id' => $this->team->id,
                    'noovo_company_id' => $this->suborganization->noovo_client_id,
                    'noovo_company_title' => $this->suborganization->noovo_client_id,
                    'noovo_team_id' => $this->team->noovo_group_id,
                    'noovo_team_title' => $this->team->noovo_group_title,
                    'projects' => json_encode($project_ids),
                    'response' => 'Projects Transfer Successful',
                    'status' => 1,
                ]);
               
                
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            // Insert Logging
            NoovoLogs::create([
                'organization_id' => $this->suborganization->id,
                'team_id' => $this->team->id,
                'noovo_company_id' => $this->suborganization->noovo_client_id,
                'noovo_company_title' => $this->suborganization->noovo_client_id,
                'noovo_team_id' => $this->team->noovo_group_id,
                'noovo_team_title' => $this->team->noovo_group_title,
                'projects' => json_encode($project_ids),
                'response' => $e->getMessage(),
                'status' => 0,
            ]);
            
        }
    }
}
