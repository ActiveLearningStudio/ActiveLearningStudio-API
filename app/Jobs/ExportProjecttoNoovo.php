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
        
        $token_response = json_decode($noovoCMSService->token);
        if (isset($token_response->result) && $token_response->result === "Failed") {
            $this->createLog([], $token_response->description, 0);
            die($token_response->description);
        }
        
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

                    $projectStatus = $this->checkProjectAlreadyMoved($project->id, $this->team->noovo_group_title, $this->team->id); 
                    
                    if ($projectStatus) continue;
                   
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
                $post['filelist'] = array(
                    "name" => $this->team->name ." Projects-" . uniqid(),
                    "description" => $this->team->name ." Projects"
                    );
                \Log::info($post);
                // Uploads files into Noovo CMS



                if (count($post['files']) > 0) {
                    $upload_file_result = $this->noovoCMSService->uploadMultipleFilestoNoovo($post);
                    $decoded_upload_result = json_decode($upload_file_result);
                    if ($decoded_upload_result->result === "Failed") {
                        $this->createLog($project_ids, $decoded_upload_result->description, 0);
                        return false;
                    }
                    $this->createLog($project_ids, 'Projects Transfer Successful', 1);
                    return false;
                }

                
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            
            $this->createLog($project_ids, $e->getMessage(), 0);
            
        }
    }

    /**
     * param array $projects
     * param string $response
     * param bool $status
     */
    private function createLog (array $projects, string $response, bool $status)
    {
        NoovoLogs::create([
            'organization_id' => $this->suborganization->id,
            'team_id' => $this->team->id,
            'noovo_company_id' => $this->suborganization->noovo_client_id,
            'noovo_company_title' => $this->suborganization->noovo_client_id,
            'noovo_team_id' => $this->team->noovo_group_id,
            'noovo_team_title' => $this->team->noovo_group_title,
            'projects' => json_encode($projects),
            'response' => $response,
            'status' => $status,
        ]);
    }

    /**
     * @param integer $project_id
     * @param string $group_title
     * @param integer $team_id
     * 
     * @return bool 
     */
    private function checkProjectAlreadyMoved(int $project_id, string $group_title, int $team_id)
    {
        $noovoLogs = NoovoLogs::where('team_id',$team_id)->where('noovo_team_title',$group_title)->where('status',1)->get();
        \Log::info($noovoLogs);
        foreach ($noovoLogs as $log) {
            $projectsArr = json_decode($log->projects);
            \Log::info($projectsArr);
           if (in_array($project_id, $projectsArr)) {
                return true;
           }
        }
        return false;
    }
}
