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

class MobileAppAjaxController extends Controller
{
    public function finish(Request $request)
    {
        $content_id = $request->input('contentId');
        $email = $request->input('email');
        if (!$content_id) {
            H5PCore::ajaxError('Invalid content');
            exit;
        }

        // if (!wp_verify_nonce(filter_input(INPUT_GET, 'token'), 'h5p_result')) {
        //     H5PCore::ajaxError('Invalid security token');
        //     exit;
        // }

        $rs = DB::select("
            SELECT id
			FROM mobile_app_h5p_results
			WHERE email = ?
			AND content_id = ?
        ", [$email, $content_id]);

        $result_id = is_array($rs) && count($rs) > 0 ? $rs[0]->id : NULL;

        $table = 'mobile_app_h5p_results';
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
        // return $result_id;
        if (!$result_id) {
            // Insert new results
            $data['email'] = $email;
            $format[] = '%d';
            $data['content_id'] = $content_id;
            $format[] = '%d';
            DB::table($table)->insert($data);
        } else {
            // Update existing results
            DB::table($table)->where('id', $result_id)->update($data);
        }

        // Get content info for log
        // $row = DB::select("
        //     SELECT c.title, l.name, l.major_version, l.minor_version
        //     FROM h5p_contents c
        //     JOIN h5p_libraries l ON l.id = c.library_id
        //     WHERE c.id = ?
        // ", [$content_id]);

        // $content = is_array($row) && count($row) > 0 ? $row[0] : NULL;

        // // Log view
        // event(new H5pEvent(
        //     'results',
        //     'set',
        //     $content_id,
        //     $content->title,
        //     $content->name,
        //     $content->major_version . '.' . $content->minor_version
        // ));

        // Success
        \H5PCore::ajaxSuccess();
        exit;
    }
}
