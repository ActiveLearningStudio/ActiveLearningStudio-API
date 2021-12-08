<?php

namespace App\Services;

/**
 * Interface for Get Whiteboard URL
 */
interface NoovoCMSInterface
{
    /**
     * To get Authentication Token for Noovo
     * @return string token
    */
    public function getNoovoCMSToken();

    /**
     * To Upload Curriki zip projects into Noovo
     * 
     * @param array $data
     * 
     * @return array $return_arr uploaded file ids
     */
    public function uploadMultipleFilestoNoovo($data);
    
    /**
     * Create the file list of uploaded files
     * @param array $export_file
    */
    public function createFileList($data);

    /**
     * Attach the file list to group
     *
     * @param array $data
     * 
    */
    public function setFileListtoGroup($data);
}
