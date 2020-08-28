<?php
namespace App\Http\Controllers\Api\V1\CurrikiGo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CurrikiGo\Moodle\Playlist as MoodlePlaylist;
use App\CurrikiGo\Canvas\Playlist as CanvasPlaylist;
use App\Models\Playlist;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepository;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use App\CurrikiGo\Canvas\Client;
use Validator;

class PublishController extends Controller
{
    private $lmsSettingRepository;

    /**
     * ProjectController constructor.
     *
     * @param ProjectRepositoryInterface $projectRepository
     */
    public function __construct(LmsSettingRepositoryInterface $lmsSettingRepository)
    {
        $this->lmsSettingRepository = $lmsSettingRepository;

        //$this->authorizeResource(Project::class, 'project');
    }
    public function playlistToMoodle(Request $request)
    {                

        $validator = Validator::make($request->all(), ['setting_id' => 'required', 'playlist_id'=> 'required']);

        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(['error' => $messages]);
        }
        
        $moodle_palylist = new MoodlePlaylist($request->input("setting_id"));
        $counter = $request->input("counter") ? $request->input("counter") : 0;
        $response = $moodle_palylist->send(['playlist_id' => $request->input("playlist_id"), 'counter' => intval($counter)]);

        $code = $response->getStatusCode();
        if($code == 200){
            $outcome = $response->getBody()->getContents();
            return responseSuccess( json_decode($outcome) );
        }else {
            return responseError(['message'=>'Error sending playlists to Moodle']);
        }
    }

    public function playlistToCanvas(Playlist $playlist, Request $request)
    {
        $validator = Validator::make($request->all(), ['setting_id' => 'required']);
        
        if ($validator->fails()) {
            $messages = $validator->messages();
            return responseError(['error' => $messages]);
        }

        $lmsSettings = $this->lmsSettingRepository->find($request->input('setting_id'));
        $canvas_client = new Client($lmsSettings);
        $canvas_playlist = new CanvasPlaylist($canvas_client);
        $counter = $request->input("counter") ? $request->input("counter") : 0;
        $outcome = $canvas_playlist->send($playlist, ['counter' => intval($counter)]);
        
        if ($outcome){            
            return response([
                'playlists' => $outcome,
            ], 200);
        } else {
            return response([
                'errors' => ['Error sending playlists to canvas'],
            ], 500);
        }
    }
}
