<?php

namespace Djoudi\LaravelH5p\Http\Controllers;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Models\H5pBrightCoveVideoContents;
use App\Models\Integration\BrightcoveAPISetting;
use App\Repositories\Integration\BrightcoveAPISettingRepository;
use Djoudi\LaravelH5p\Events\H5pEvent;
use H5PContentValidator;
use H5PCore;
use H5PEditorEndpoints;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class AjaxController extends Controller
{
    private static $hasWYSIWYGEditor = array(
        'H5P.CoursePresentation',
        'H5P.InteractiveVideo',
        'H5P.DragQuestion'
    );

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

    public function loadAllDependencies(Request $request)
    {
        // headers for CORS
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

        $machineName = $request->get('machineName');
        $majorVersion = $request->get('majorVersion');
        $minorVersion = $request->get('minorVersion');
        $parameters = $request->get('parameters');
        $h5p = App::make('LaravelH5p');

        $libraryData = $this->getLibraryData($machineName, $majorVersion, $minorVersion, $parameters, $h5p);
            // Log library load
            event(new H5pEvent(
                'library',
                NULL,
                NULL,
                NULL,
                $machineName,
                $majorVersion . '.' . $minorVersion
            ));

         // brightcove settings css
        if ($machineName === 'H5P.BrightcoveInteractiveVideo') {
            $brightcoveApiSettingId = $request->get('brightcoveApiSettingId');
            $contentId = $request->get('contentId');
            if (empty($brightcoveApiSettingId)) {
                $brightcoveContentData = H5pBrightCoveVideoContents::where('h5p_content_id', $contentId)->first();
                if ($brightcoveContentData) {
                    $brightcoveApiSettingId = $brightcoveContentData->brightcove_api_setting_id;
                }
            }

            if ($brightcoveApiSettingId) {
                $brightcoveAPISettingRepository = new BrightcoveAPISettingRepository(new BrightcoveAPISetting());
                $brightcoveAPISetting = $brightcoveAPISettingRepository->find($brightcoveApiSettingId);
                $libraryData->css[] = config('app.url') . $brightcoveAPISetting->css_path;
            }
        }
        H5PCore::ajaxSuccess($libraryData, TRUE);

    }

    public function singleLibrary(Request $request)
    {
        $h5p = App::make('LaravelH5p');
        $editor = $h5p::$h5peditor;
        $editor->ajax->action(H5PEditorEndpoints::SINGLE_LIBRARY, $request->get('_token'));
    }

    public function contentTypeCache(Request $request)
    {
        $h5p = App::make('LaravelH5p');
        $editor = $h5p::$h5peditor;
        $editor->ajax->action(H5PEditorEndpoints::CONTENT_TYPE_CACHE, $request->get('_token'));
    }

    public function libraryInstall(Request $request)
    {
        $h5p = App::make('LaravelH5p');
        $editor = $h5p::$h5peditor;
        // $editor->ajax->action(H5PEditorEndpoints::LIBRARY_INSTALL, $request->get('_token'), $request->get('machineName'));
        $machineName = $request->get('id');
        $token = csrf_token();
        $editor->ajax->action(H5PEditorEndpoints::LIBRARY_INSTALL, $token, $machineName);
    }

    public function libraryUpload(Request $request)
    {
        $filePath = $request->file('h5p')->getPathName();
        $h5p = App::make('LaravelH5p');
        $editor = $h5p::$h5peditor;
        $token = csrf_token();
        // $editor->ajax->action(H5PEditorEndpoints::LIBRARY_UPLOAD, $request->get('_token'), $filePath, $request->get('contentId'));
        $editor->ajax->action(H5PEditorEndpoints::LIBRARY_UPLOAD, $token, $filePath, $request->get('contentId'));
    }

    public function files(Request $request)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            // headers for CORS
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
            return;
        }
        $fieldParam = json_decode($request->get('field'));
        if (json_last_error() === JSON_ERROR_NONE) {
            $filePath = $request->file('file');
            $h5p = App::make('LaravelH5p');
            $editor = $h5p::$h5peditor;
            $token = csrf_token();
            // $editor->ajax->action(H5PEditorEndpoints::FILES, $request->get('_token'), $request->get('contentId'));
            $editor->ajax->action(H5PEditorEndpoints::FILES, $token, $request->get('contentId'));
        } else {
            throw new GeneralException('Invalid json format of field param!');
        }
    }

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

    public function contentUserData(Request $request)
    {
        return response()->json($request->all());
    }

    private function getLibraryData($machineName, $majorVersion, $minorVersion, $parameters, $h5p)
    {
        $core = $h5p::$core;
        $editorStorage = $h5p::$editorStorage;
        $libraryData = new stdClass();
        if ($machineName) {
            $library = $core->loadLibrary($machineName, $majorVersion, $minorVersion);

            // Include name and version in data object for convenience
            $libraryData->name = $machineName;
            $libraryData->version = (object)array('major' => $majorVersion, 'minor' => $minorVersion);
            $libraryData->title = $library['title'];


            $libraries = $this->findLibraryDependencies($machineName, $library, $core, $parameters, $libraryData);

            // Temporarily disable asset aggregation
            $aggregateAssets = $core->aggregateAssets;
            $core->aggregateAssets = FALSE;

            // Get list of JS and CSS files that belongs to the dependencies
            $files = $core->getDependenciesFiles($libraries);
            $editorStorage->alterLibraryFiles($files, $libraries);

            // Restore asset aggregation setting
            $core->aggregateAssets = $aggregateAssets;

            // Create base URL
            $url = $core->url;

            // Javascripts
            if (!empty($files['scripts'])) {
                foreach ($files['scripts'] as $script) {
                    if (preg_match('/:\/\//', $script->path) === 1) {
                        // External file
                        $libraryData->javascript[] = $script->path . $script->version;
                    } else {
                        // Local file
                        $path = $url . $script->path;
                        if (!isset($core->h5pD)) {
                            $path .= $script->version;
                        }
                        $libraryData->javascript[] = $path;
                    }
                }
            }

            // Stylesheets
            if (!empty($files['styles'])) {
                foreach ($files['styles'] as $css) {
                    if (preg_match('/:\/\//', $css->path) === 1) {
                        // External file
                        $libraryData->css[] = $css->path . $css->version;
                    } else {
                        // Local file
                        $path = $url . $css->path;
                        if (!isset($core->h5pD)) {
                            $path .= $css->version;
                        }
                        $libraryData->css[] = $path;
                    }
                }
            }


        }
        return $libraryData;
    }

    private function findLibraryDependencies($machineName, $library, $core, $parameters, $libraryData)
    {
        // Validate and filter against main library semantics.
        $validator = new H5PContentValidator($core->h5pF, $core);
        $params = (object) array(
            'library' => H5PCore::libraryToString($library),
            'params' => json_decode(json_encode(json_decode($parameters)->params))
        );

        if (!$params->params) {
            return NULL;
        }

        $validator->validateLibrary($params, (object) array('options' => array($params->library)));

        $libraryData->filtered = json_encode($params->params);
        $dependencies = $validator->getDependencies();

        // Load addons for wysiwyg editors
        if (in_array($machineName, self::$hasWYSIWYGEditor)) {
            $addons = $core->h5pF->loadAddons();
            foreach ($addons as $addon) {
                $key = 'editor-' . $addon['machineName'];
                $dependencies[$key]['weight'] = sizeof($dependencies)+1;
                $dependencies[$key]['type'] = 'editor';
                $dependencies[$key]['library'] = $addon;
            }
        }

        // Order dependencies by weight
        $orderedDependencies = array();
        for ($i = 1, $s = count($dependencies); $i <= $s; $i++) {
            foreach ($dependencies as $dependency) {
                if ($dependency['weight'] === $i && $dependency['type'] === 'preloaded') {
                    $dependency['library']['id'] = $dependency['library']['libraryId'];
                    $orderedDependencies[$dependency['library']['libraryId']] = $dependency['library'];
                    break;
                }
            }
        }
        return $orderedDependencies;
    }
}
