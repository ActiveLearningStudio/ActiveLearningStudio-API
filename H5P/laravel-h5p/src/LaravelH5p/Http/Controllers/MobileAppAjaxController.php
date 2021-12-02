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
        // get content id of the activity
        $content_id = $request->input('contentId');
        $email = $request->input('email');
        if (!$content_id) {
            H5PCore::ajaxError('Invalid content');
            exit;
        }

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

        // Set time = 0 if It's not available
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
        // Check for if record already exists
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

        // Success
        \H5PCore::ajaxSuccess();
        exit;
    }
}
