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
    protected $project;

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
    public function __construct(User $user, Project $project, $noovoCMSService, Team $team, Organization $suborganization)
    {
        
       $this->user = $user;
        $this->project = $project;
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
                
                // Create the zip archive of folder
                $export_file = $projectRepository->exportProject($this->user, $this->project);
                \Log::Info($export_file);
                $file_info = array(
                    "filename" => str_replace(' ', '-', strtolower($this->project->name)) . uniqid(),
                    "description"=> $this->project->description,
                    "url"=> url(Storage::url('exports/'.basename($export_file))),
                    "md5sum"=> md5_file($export_file)
                );
                array_push($files_arr, $file_info);
                array_push($project_ids, $this->project->id);
                

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
                    $this->createLog($project_ids, 'Project Transfer Successful', 1);
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
    private function createLog(array $projects, string $response, bool $status)
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
     * @param integer $projectId
     * @param string $groupTitle
     * @param integer $teamId
     * 
     * @return bool 
     */
    private function checkProjectAlreadyMoved(int $projectId, string $groupTitle, int $teamId)
    {
        $noovoLogs = NoovoLogs::where('team_id',$teamId)->where('noovo_team_title',$groupTitle)->where('status',1)->get();
        \Log::info($noovoLogs);
        foreach ($noovoLogs as $log) {
            $projectsArr = json_decode($log->projects);
            \Log::info($projectsArr);
           if (in_array($projectId, $projectsArr)) {
                return true;
           }
        }
        return false;
    }
}
