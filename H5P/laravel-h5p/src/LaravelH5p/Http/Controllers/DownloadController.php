<?php

namespace Djoudi\LaravelH5p\Http\Controllers;

use App\Http\Controllers\Controller;
use Djoudi\LaravelH5p\Events\H5pEvent;
use Djoudi\LaravelH5p\LaravelH5p;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/**
 * @group 12. H5P
 *
 * APIs for H5P management
 */
class DownloadController extends Controller
{
    /**
     * Download H5P
     *
     * Download the specified H5P
     *
     * @urlParam id required The Id of a H5P content Example 1
     *
     * @response {
     *  "Content-Type" => "application/zip",
     *  "Cache-Control" => "no-store, no-cache, must-revalidate, post-check=0, pre-check=0"
     * }
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function __invoke(Request $request, $id)
    {
        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;
        $interface = $h5p::$interface;

        $content = $core->loadContent($id);
        $content['filtered'] = '';
        $params = $core->filterParameters($content);

        /*event(new H5pEvent(
            'download',
            NULL,
            $content['id'],
            $content['title'],
            $content['library']['name'],
            $content['library']['majorVersion'] . '.' . $content['library']['minorVersion']
        ));*/

        return response()->download($interface->_download_file, '', [
            'Content-Type' => 'application/zip',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
        ]);
    }
}
