<?php

namespace App\Services;

use App\Exceptions\GeneralException;
use App\Services\NoovoCMSInterface;
use Illuminate\Support\Facades\Http;

/**
 * Call Noovo API Service class
 */
class NoovoCMSService implements NoovoCMSInterface
{
    /**
     * @var string
     */
    public $token;
    
    /**
     * @var string
     */
    public $host;

   

    /**
     * NoovoCMSService constructor.
     */
    public function __construct()
    {
        $this->host =  config('noovo.host');
        $this->token = $this->getNoovoCMSToken();
    }

    /**
     * To get Authentication Token
     * @return string token
     */
    public function getNoovoCMSToken()
    {
        
        $host = $this->host . ":8082/auth";
        

        $username = config('noovo.username');
        $password = config('noovo.password');
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$host);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ['email' =>$username,'pwd' => $password]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_VERBOSE,true);
        curl_setopt($ch, CURLINFO_HTTP_CODE, true);
        $result=curl_exec ($ch);
        
        curl_close ($ch);
        
        $this->token = json_decode($result)->data->authorization;
        return  json_decode($result)->data->authorization;

    }

    /**
     * Upload exported file to Noovo
     *
     * @param string $export_file
     * @param object $project
     */
    public function uploadFileToNoovo ($export_file, $project) { 

        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->host . ':8088/file');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        $post = array(
            'filename' => $project->name,
            'description' => $project->description,
            'gid' => "298",
            'file' => curl_file_create($export_file)
            
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $headers = array();
        $headers[] = 'Authorization: '. $this->token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        
        $response = $data = json_decode($result)->data;
        return $response->id;

    }

    /**
     * Create the file list of uploaded files
     *
     * @param array $export_file
     * 
    */
    public function createFileList($data) {
        
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $this->host . ":8088/filelist");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $headers = array();
        $headers[] = 'Authorization: '.$this->token;
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
       
        
    }

    public function uploadMultipleFilestoNoovo($data)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->host . ':8088/file/uploads');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        
        //echo "<pre>";print_r(json_encode($post)); die;
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $headers = array();
        $headers[] = 'Authorization: ' . config('noovo.file_upload_token');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        
        \Log::info(json_decode($result)->data);
        $response_data = json_decode($result)->data;

        $return_arr = [];
        foreach ($response_data as $file_rec) {
            array_push($return_arr, $file_rec->id );
        }

        return $return_arr;

    }

}
