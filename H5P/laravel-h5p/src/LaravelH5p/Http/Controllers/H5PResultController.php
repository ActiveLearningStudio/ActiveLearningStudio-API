<?php

namespace Djoudi\LaravelH5p\Http\Controllers;

use App\Http\Controllers\Controller;
use Djoudi\LaravelH5p\Events\H5pEvent;
use Djoudi\LaravelH5p\LaravelH5p;
use Djoudi\LaravelH5p\Helpers\H5pResultHelper;
use H5PEditorEndpoints;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class H5PResultController extends Controller
{
    public function my(Request $request)
    {
        $user = Auth::user();
        return response()->json(H5pResultHelper::get_results(null, $user->id));
    }
}
