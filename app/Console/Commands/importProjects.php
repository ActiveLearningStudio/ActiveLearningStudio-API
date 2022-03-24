<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\User;
use App\Models\Playlist;
use App\CurrikiGo\Moodle\Playlist as PlaylistMoodle;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepository;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use App\Models\CurrikiGo\LmsSetting;


class importProjects extends Command
{
    
    /**
     * $lmsSettingRepository
     */
    private $lmsSettingRepository;

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
     * @param LmsSettingRepositoryInterface $lmsSettingRepository
     * @return void
     */
    public function __construct(LmsSettingRepositoryInterface $lmsSettingRepository)
    {
        $this->lmsSettingRepository = $lmsSettingRepository;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return object
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
        $encodedResponse = json_decode($response,true);

        // map-device-lti-client-id
        
        $lms_settings = LmsSetting::where('lti_client_id',config('constants.map-device-lti-client-id'))->first();
        
        if (empty($lms_settings)) {
            $responseReturn =  response([
                'errors' => ['Missing or Invalid LTI CLient ID'],
            ], 500);

            return $this->info($responseReturn);
        }

        if (isset($encodedResponse['project_id']) && !empty($encodedResponse['project_id'])) {
            // Code for Moodle Import
            $playlists = Playlist::where('project_id',$encodedResponse['project_id'])->get();
            
            foreach ($playlists as $playlist) {
                // Publish playlist into moodle
                $lmsSetting = $this->lmsSettingRepository->find($lms_settings->id);
                $counter = 0;
                $playlistMoodleObj = new PlaylistMoodle($lmsSetting);
                $responseReq = $playlistMoodleObj->send($playlist, ['counter' => intval($counter)]);

                $code = $responseReq->getStatusCode();
                if ($code == 200) {
                    $outcome = $responseReq->getBody()->getContents();
                    $responseReturn = response([
                        'data' => json_decode($outcome),
                    ], 200);
                    $this->info($responseReturn);
                } else {
                    $responseReturn =  response([
                        'errors' => ['Failed to send playlist to Moodle.'],
                    ], 500);
                    $this->info($responseReturn);
                }

            }
        }
        
    }
}
