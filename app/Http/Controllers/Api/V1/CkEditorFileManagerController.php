<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator,Redirect,Response,File;
use Storage;

class CkEditorFileManagerController extends Controller
{
    
    /**
     * CkEditorFileManagerController constructor.
     */
    public function __construct()
    {
            //
    }

    /**
     * Upload file from ckeditor
     * 
     * Upload file from ckeditor into ckeditor folder
     *
     * @param Request $request
     *
     * @bodyParam upload required File of type doc/docx/pdf/txt
     * 
     * @responseFile responses/ckeditor/upload-file.json
     *
     * @response 404 {
     *   "errors": [
     *     "Fail to upload file."
     *   ]
     * }
     *
     * @param Request $request
     * 
     * @return string
     * 
     */
    public function uploadFile(Request $request)
    {
        if($request->hasFile('upload')) {
            
            $validator = Validator::make($request->all(), 
                                        [ 
                                            'upload' => 'required|mimes:doc,docx,pdf,xlsx',
                                        ]);   
 
            if ($validator->fails()) {          
                    return response()->json(['error'=>$validator->errors()], 401);                        
            }
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time() . '.' . $extension;
        
            $request->file('upload')->move(storage_path('app/public/ckeditor'), $fileName);
        
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = url(Storage::url('ckeditor/' . basename($fileName)));
            
            $url = str_replace('storage', 'api/storage', $url);
            $msg = 'Document uploaded successfully'; 
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
               
            @header('Content-type: text/html; charset=utf-8'); 
            echo $response;
        }
    }

    /**
     * Browse files from ckeditor
     * 
     * Browse files from ckeditor folder
     *
     * @param Request $request
     *
     * @responseFile responses/ckeditor/browse-file.json
     *
     * @param Request $request
     * @return string
     * 
     */
    public function browseFiles(Request $request)
    {
        $path = storage_path('app/public/ckeditor');
        $filesInFolder = File::allFiles($path);
        $CKEditorFuncNum = $request->input('CKEditorFuncNum');
        $return_html = "<table border='1' align='center' style='border-collapse: collapse;margin: 25px 0;
        font-size: 0.9em;
        font-family: sans-serif;
        min-width: 400px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);'><tr><th>Name</th><th>Created At</th><th>Action</th></tr>";
        
        foreach ($filesInFolder as $key => $path) {
            
            $files = pathinfo($path);
            
            $allMedia[] = $files['basename'];
            $fileUrl =  url(Storage::url('ckeditor/' . basename($files['basename'])));
            $fileUrl = str_replace('storage', 'api/storage', $fileUrl);
            $return_html .= "<tr><td> ".$files['basename']." </td><td>" . date('Y-m-d H:i:s', $path->getATime()) . "</td><td><a href='' onclick='window.opener.CKEDITOR.tools.callFunction( $CKEditorFuncNum, \"$fileUrl\" );window.close();'>Select</a></td></tr>";
          }
          $return_html .= "</table>";
          echo $return_html;
    }
}
