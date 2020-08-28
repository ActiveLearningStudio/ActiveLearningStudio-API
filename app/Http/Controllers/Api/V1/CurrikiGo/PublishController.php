<?php
namespace App\Http\Controllers\Api\V1\CurrikiGo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CurrikiGo\Moodle\Playlist as MoodlePlaylist;
use App\CurrikiGo\Canvas\Playlist as CanvasPlaylist;
use Validator;

class PublishController extends Controller
{
    
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

        $canvas_playlist = new CanvasPlaylist($playlist);
        $counter = $request->input("counter") ? $request->input("counter") : 0;
        $outcome = $canvas_playlist->send(['playlist_id' => $request->input("playlist_id"), 'counter' => intval($counter)]);
        
        if($outcome){            
            return responseSuccess($outcome);
        }else {
            return responseError(['message'=>'Error sending playlists to canvas']);
        }
    }
}
