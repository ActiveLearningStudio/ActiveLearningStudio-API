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
     * Upload exported file to Noovo
     *
     * @param string $export_file
     * @param object $project
    */
    public function uploadFileToNoovo ($export_file, $project);
    
    /**
     * Create the file list of uploaded files
     * @param array $export_file
    */
    public function createFileList($data);
}
