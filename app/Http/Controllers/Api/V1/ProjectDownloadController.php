<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Models\Project;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use App\Models\Organization;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Djoudi\LaravelH5p\Events\H5pEvent;

class ProjectDownloadController extends Controller
{
    /**
     * ErrorController constructor.
     */
    public function __construct(User $user, Project $project)
    {
        $this->user = $user;
        $this->project = $project;
    }

    /**
     * Display error
     *
     * @response 401 {
     *   "errors": [
     *     "Unauthorized."
     *   ]
     * }
     *
     * @return Response
     */
    public function exportProject(Request $request, Organization $suborganization, Project $project)
    {
        $zip = new ZipArchive;
        $user = Auth::user();

        // Check if Project Thumbnail exists
        if (filter_var($project->thumb_url, FILTER_VALIDATE_URL) == false) {
            $project_thumbanil =  storage_path("app/public/" . (str_replace('/storage/', '', $project->thumb_url)));
            $ext = pathinfo(basename($project_thumbanil), PATHINFO_EXTENSION);
            $data = file_get_contents($project_thumbanil);
            $project->thumb_url = 'data:image/' . $ext . ';base64,' . base64_encode($data);
        }
        $project->playlist_count = count($project->playlists);
        // Create Project.json file
        $project_dir_name = 'projects-'.uniqid();
        Storage::disk('public')->put('/exports/'.$project_dir_name.'/project.json', $project);
       
        $playlists = $project->playlists;
        $get_activities_count = 0;
        // get playlists from project
        foreach ($playlists as $playlist) {
            // Add playlist.json file
            $title = $playlist->title;
            $playlist->activity_count = count($playlist->activities);
            Storage::disk('public')->put('/exports/'.$project_dir_name.'/playlists/'.$title.'/'.$title.'.json', $playlist);
            $activites = $playlist->activities;
            // return $playlist;
            $get_activities_count += count($activites);

            foreach($activites as $key => $activity) {
                // Check if activity has the thumbnails
                // return $key;
                if (filter_var($activity->thumb_url, FILTER_VALIDATE_URL) == false) {
                    $activity_thumbanil =  storage_path("app/public/" . (str_replace('/storage/', '', $activity->thumb_url)));
                    $activity_ext = pathinfo(basename($activity_thumbanil), PATHINFO_EXTENSION);
                    $activity->thumb_url = 'data:image/' . $activity_ext . ';base64,' . base64_encode(file_get_contents($activity_thumbanil));
                }
                // Add activity.json file
                Storage::disk('public')->put('/exports/'.$project_dir_name.'/playlists/'.$title.'/activities/'.$activity->title.'.json', $activity);
                // Load h5p
                $h5p = App::make('LaravelH5p');
                $core = $h5p::$core;
                $settings = $h5p::get_editor();
                $content = $h5p->load_content($activity->h5p_content_id);
                $content['enable'] = 8; // always enable H5P frame which include 'Reuse' and 'Embed' links
                $embed = $h5p->get_embed($content, $settings);
                $embed_code = $embed['embed'];
                $settings = $embed['settings'];
                $h5p_json ['settings'] = $settings;
                $h5p_json['user'] = $user;
                $h5p_json['embed_code'] = $embed_code;
                
                // return $h5p_json;
                Storage::disk('public')->put('/exports/'.$project_dir_name.'/playlists/'.$title.'/activities/'.$activity->title.'/'.$activity->h5p_content_id.'-h5p.json', json_encode($h5p_json));

                $decoded_content = json_decode($activity->h5p_content,true);
                $decoded_content['library_title'] = \DB::table('h5p_libraries')->where('id', $decoded_content['library_id'])->value('name');
                $decoded_content['library_major_version'] = \DB::table('h5p_libraries')->where('id', $decoded_content['library_id'])->value('major_version');
                $decoded_content['library_minor_version'] = \DB::table('h5p_libraries')->where('id', $decoded_content['library_id'])->value('minor_version');

                Storage::disk('public')->put('/exports/'.$project_dir_name.'/playlists/'.$title.'/activities/'.$activity->title.'/'.$activity->h5p_content_id.'.json', json_encode($decoded_content));

                \File::copyDirectory( storage_path('app/public/h5p/content/'.$activity->h5p_content_id), storage_path('app/public/exports/'.$project_dir_name.'/playlists/'.$title.'/activities/'.$activity->title.'/'.$activity->h5p_content_id) );
                // return $activity->h5p_content_id;
            } 
        }
        $project->activities_count = $get_activities_count;
        Storage::disk('public')->put('/exports/'.$project_dir_name.'/project.json', $project);
        // Get real path for our folder
        $rootPath = storage_path('app/public/exports/'.$project_dir_name);
        
        // Initialize archive object
        // $zip = new ZipArchive();
        $fileName = $project_dir_name.'.zip';
        $zip->open(storage_path('app/public/exports/'.$fileName), ZipArchive::CREATE | ZipArchive::OVERWRITE);
        
        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        
        foreach ($files as $name => $file)
        {
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
        
                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }
        
        // Zip archive will be created only after closing object
        $zip->close();

        return url('storage/exports/'.$fileName);
    }

    public function deleteProject(Request $request, $projectPath)
    {
        Storage::disk('public')->delete('/exports/'.$projectPath.'.zip');
        return Storage::disk('public')->deleteDirectory('/exports/'.$projectPath);
    }
}
