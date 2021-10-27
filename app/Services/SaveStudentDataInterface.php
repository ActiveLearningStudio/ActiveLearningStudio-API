<?php

namespace App\Services;

/**
 * Interface for Save Student Data service
 */
interface SaveStudentDataInterface
{
     /**
     * Save student data into external database
     *
     * @param array $request
     */
    public function saveStudentData($request);

}
