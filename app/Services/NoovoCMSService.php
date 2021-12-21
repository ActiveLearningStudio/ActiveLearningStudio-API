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
        //$this->token = $this->getNoovoCMSToken();  // Commenting as it will use in V2 of Noovo API
    }

    /**
     * To get Authentication Token
     * 
     * @return string token
     */
    public function getNoovoCMSToken()
    {
        $host = $this->host . ":8082/auth"; // noovo have different ports for different api's. We will remove it once they finalize their api
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
        
        if (curl_errno($ch)) {
            \Log::error(curl_error($ch));
            return;
        }
        curl_close ($ch);
        $json_result = json_decode($result);
        if ($json_result->result === "Failed") {
            return $result;
        }
        $this->token = $json_result->data->authorization;
        return  $json_result->data->authorization;
    }

    /**
     * Create the file list of uploaded files
     *
     * @param array $export_file
     * 
    */
    public function createFileList($data) 
    {
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
            \Log::error(curl_error($ch));
            return;
        }
        curl_close($ch);

        return $result;
    }

    /**
     * To Upload Curriki zip projects into Noovo
     * 
     * @param array $data
     * 
     * @return array $return_arr uploaded file ids
     */
    public function uploadMultipleFilestoNoovo($data)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->host . ':8088/file/integration');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $headers = array();
        $headers[] = 'Authorization: ' . config('noovo.file_upload_token');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            \Log::error(curl_error($ch));
            return;
        }
        curl_close($ch);
        
        \Log::info($result);
    
        return $result;
    }

    /**
     * Attach the file list to group
     *
     * @param array $data
     * 
    */
    public function setFileListtoGroup($data) 
    {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $this->host . ":8088/group/filelist");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $headers = array();
        $headers[] = 'Authorization: '.$this->token;
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        \Log::info($result);
        if (curl_errno($ch)) {
            \Log::error(curl_error($ch));
            return;
        }
        curl_close($ch);

        return $result;
    }

}
