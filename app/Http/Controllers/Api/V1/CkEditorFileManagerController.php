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
     * Upload file from ckeditor into ckeditor folder
     *
     * @param Request $request
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
     * @return string
     * 
     */
    public function uploadFile(Request $request)
    {
        $validator = Validator::make($request->all(), 
              [ 
              'file' => 'required|mimes:doc,docx,pdf,txt|max:2048',
             ]);   
 
        if ($validator->fails()) {          
            return response()->json(['error'=>$validator->errors()], 401);                        
         } 

        if ($files = $request->file('file')) {
             
            //store file into ckeditor folder
            $file = $request->file->store('public/ckeditor');
            $url =  url(Storage::url('projects/' . basename($file)));
            $message = "";
            $function_number = $request->get('CKEditorFuncNum');
            
            // Code snippt to redirect the uploaded image url on Link Info
            echo "<script type='text=javascript'>window.parent.CKEDITOR.tools.callFunction($function_number, '$url', '$message')</script>";
  
        }
    }
}
