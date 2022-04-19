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
     * @bodyParam upload file of type doc/docx/pdf/txt
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
}
