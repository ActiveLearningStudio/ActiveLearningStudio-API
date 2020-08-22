<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(isset($_GET['import']) && $_GET['import'] == 'h5p'){
            $dir = "currikiserver.php";
            $all_files = File::allFiles( public_path($dir) );
            foreach ($all_files as $file) {
                require_once $file->getPathname();
            }
            
            foreach ($all_files as $file) {
                $file = $file->getFilename();
                $filename = pathinfo($file)['filename'];
                $data = ${$filename};
                if( count($data) > 0 ){
                    DB::table($filename)->insert($data);
                }
            }

            dd("Done!");
        }

        return view('home');
    }
}
