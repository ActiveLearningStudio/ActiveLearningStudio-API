<?php

namespace Djoudi\LaravelH5p\Http\Controllers;

use App\Http\Controllers\Controller;
use Djoudi\LaravelH5p\Events\H5pEvent;
use Djoudi\LaravelH5p\LaravelH5p;
use H5PEditorEndpoints;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * @group 12. H5P
 *
 * APIs for H5P management
 */
class AjaxController extends Controller
{
    /**
     * Get H5P libraries
     *
     * Get a list of libraries
     * 
     * @urlParam machineName H5P.Dictation 1.0
     * @urlParam majorVersion Example 1
     * @urlParam minorVersion Example 0
     * 
     * @responseFile responses/h5p/h5p-ajax-libraries.json
     *
     * @return Response
     */
    public function libraries(Request $request)
    {
        // headers for CORS
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        
        $machineName = $request->get('machineName');
        $major_version = $request->get('majorVersion');
        $minor_version = $request->get('minorVersion');

        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;
        $editor = $h5p::$h5peditor;

        if ($machineName) {
            $editor->ajax->action(
                H5PEditorEndpoints::SINGLE_LIBRARY,
                $machineName,
                $major_version,
                $minor_version,
                $h5p->get_language(),
                '',
                $h5p->get_h5plibrary_url('', TRUE),
                ''
            );

            // Log library load
            event(new H5pEvent(
                'library',
                NULL,
                NULL,
                NULL,
                $machineName,
                $major_version . '.' . $minor_version
            ));
            exit;
        } else {
            // Otherwise retrieve all libraries
            $editor->ajax->action(H5PEditorEndpoints::LIBRARIES);
            exit;
        }
    }

    /**
     * Get single H5P library
     *
     * Get single H5P library details
     *
     * @urlParam machineName string H5P.Dictation 1.0
     * @urlParam majorVersion string Example 1
     * @urlParam minorVersion string Example 0
     * 
     * @responseFile responses/h5p/h5p-ajax-library.json
     *
     * @return Response
     */
    public function singleLibrary(Request $request)
    {
        $h5p = App::make('LaravelH5p');
        $editor = $h5p::$h5peditor;
        $editor->ajax->action(H5PEditorEndpoints::SINGLE_LIBRARY, $request->get('_token'));
    }

    /**
     * Get H5P Content Type Cache
     *
     * Get H5P Content Type Cache mainly for H5P Hub
     * 
     * @responseFile responses/h5p/h5p-ajax-content-type-cache.json
     *
     * @return Response
     */
    public function contentTypeCache(Request $request)
    {
        $h5p = App::make('LaravelH5p');
        $editor = $h5p::$h5peditor;
        $editor->ajax->action(H5PEditorEndpoints::CONTENT_TYPE_CACHE, $request->get('_token'));
    }

    /**
     * Install H5P library
     *
     * Install H5P library and list all
     *
     * @bodyParam id int required Example H5P.Dictation 1.0
     * 
     * @responseFile responses/h5p/h5p-ajax-content-type-cache.json
     *
     * @return Response
     */
    public function libraryInstall(Request $request)
    {
        $h5p = App::make('LaravelH5p');
        $editor = $h5p::$h5peditor;
        // $editor->ajax->action(H5PEditorEndpoints::LIBRARY_INSTALL, $request->get('_token'), $request->get('machineName'));
        $machineName = $request->get('id');
        $token = csrf_token();
        $editor->ajax->action(H5PEditorEndpoints::LIBRARY_INSTALL, $token, $machineName);
    }

    /**
     * Upload H5P library
     *
     * Upload H5P library and list all
     *
     * @bodyParam h5p file required Example example-h5p.h5p
     * 
     * @responseFile responses/h5p/h5p-ajax-library-upload.json
     *
     * @return Response
     */
    public function libraryUpload(Request $request)
    {
        $filePath = $request->file('h5p')->getPathName();
        $h5p = App::make('LaravelH5p');
        $editor = $h5p::$h5peditor;
        $token = csrf_token();
        // $editor->ajax->action(H5PEditorEndpoints::LIBRARY_UPLOAD, $request->get('_token'), $filePath, $request->get('contentId'));
        $editor->ajax->action(H5PEditorEndpoints::LIBRARY_UPLOAD, $token, $filePath, $request->get('contentId'));
    }

    /**
    * Upload H5P Editor files
    * 
    * Upload H5P Editor files based on content
    *
    * @bodyParam file binary required File upload on H5P Editor Example: an-image.png
    * @bodyParam field string required Upload filem meta data
    * @bodyParam contentId int required H5P content id
    *
    * @response {
    *   "height": "1080",
    *   "mime": "image/png",
    *   "path": "images/file-5f7b61fb0355b.png#tmp",
    *   "width": 1920
    * }
    *
    * @return Response
    */
    public function files(Request $request)
    {
        $filePath = $request->file('file');
        $h5p = App::make('LaravelH5p');
        $editor = $h5p::$h5peditor;
        $token = csrf_token();
        // $editor->ajax->action(H5PEditorEndpoints::FILES, $request->get('_token'), $request->get('contentId'));
        $editor->ajax->action(H5PEditorEndpoints::FILES, $token, $request->get('contentId'));
    }

    /**
    * Filter H5P
    *
    * Filter parameter values according to semantics 
    *
    * @urlParam libraryParameters string required
    *
    * @response {
    *   "success": TRUE,
    *   "data": "JsonData"
    * }
    * 
    * @return Response
    */
    public function filter(Request $request)
    {
        $token = csrf_token();
        $libraryParameters = $request->get('libraryParameters');

        $h5p = App::make('LaravelH5p');
        $editor = $h5p::$h5peditor;
        $editor->ajax->action(H5PEditorEndpoints::FILTER, $token, $libraryParameters);
        exit;
    }

    public function __invoke(Request $request)
    {
        return response()->json($request->all());
    }
    
    /**
     * Upload H5P library
     *
     * Upload H5P library and list all
     *
     * @bodyParam contentId int required H5P content id Example 1
     * @bodyParam score int required H5P score Example 2
     * @bodyParam maxScore int required H5P maximum score Example 3
     * @bodyParam opened float required H5P opened Example 3.333
     * @bodyParam finished float required H5P finished Example 8.9898
     * 
     * @response {
     *   "success": "true"
     * }
     * 
     * @response {
     *   "success": "false"
     * }
     *
     * @return Response
     */
    public function finish(Request $request)
    {
        $content_id = $request->input('contentId');
        if (!$content_id) {
            H5PCore::ajaxError('Invalid content');
            exit;
        }

        // if (!wp_verify_nonce(filter_input(INPUT_GET, 'token'), 'h5p_result')) {
        //     H5PCore::ajaxError('Invalid security token');
        //     exit;
        // }

        $user = Auth::user();
        $user_id = $user->id; /*1*/

        $rs = DB::select("
            SELECT id
			FROM h5p_results
			WHERE user_id = ?
			AND content_id = ?
        ", [$user_id, $content_id]);

        $result_id = is_array($rs) && count($rs) > 0 ? $rs[0]->id : NULL;

        $table = 'h5p_results';
        $data = array(
            'score' => $request->input('score'),
            'max_score' => $request->input('maxScore'),
            'opened' => $request->input('opened'),
            'finished' => $request->input('finished'),
            'time' => $request->input('time')
        );

        if ($data['time'] === NULL) {
            $data['time'] = 0;
        }

        $format = array(
            '%d',
            '%d',
            '%d',
            '%d',
            '%d'
        );

        if (!$result_id) {
            // Insert new results
            $data['user_id'] = $user_id;
            $format[] = '%d';
            $data['content_id'] = $content_id;
            $format[] = '%d';
            DB::table($table)->insert($data);
        } else {
            // Update existing results
            DB::table($table)->where('id', $result_id)->update($data);
        }

        // Get content info for log
        $row = DB::select("
            SELECT c.title, l.name, l.major_version, l.minor_version
            FROM h5p_contents c
            JOIN h5p_libraries l ON l.id = c.library_id
            WHERE c.id = ?
        ", [$content_id]);

        $content = is_array($row) && count($row) > 0 ? $row[0] : NULL;

        // Log view
        event(new H5pEvent(
            'results',
            'set',
            $content_id,
            $content->title,
            $content->name,
            $content->major_version . '.' . $content->minor_version
        ));

        // Success
        \H5PCore::ajaxSuccess();
        exit;
    }

    /**
     * H5P content user data 
     *
     * Save H5P content user data
     *
     * @bodyParam content_id int required H5P content id Example 1
     * @bodyParam data_type string required Example H5P-Blanks-question-important-description-open
     * @bodyParam sub_content_id int required Example 0
     * 
     * @response {
     *   "success": "true"
     * }
     *
     * @return Response
     */
    public function contentUserData(Request $request)
    {

        //TODO: actual logic
        return response([
            'success' => true
        ], 201);
    }
}
