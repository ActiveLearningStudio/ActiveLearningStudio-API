<?php

namespace App\Services;

use App\Services\SaveStudentDataInterface;
use DB;

/**
 * Save Student Data Service Service class
 */
class SaveStudentdataService implements SaveStudentDataInterface
{
    /**
     * Save student data into external Database
     *
     * @param array $request
     */
    public function saveStudentData($request)
    {
        $statement =  "'" . $request->studentId . "' , '" . trim(str_replace(['"',"'"], "", $request->customPersonNameGiven), '\'"') . "' , '" . trim(str_replace(['"',"'"], "", $request->customPersonNameFamily), '\'"') . "'";
        if (config('student-data.run_dev_proc')) {
            DB::connection('pgsql-cust')->select("call dev_dcmg199iaigp51_updi ($statement) ");
        } else {
            DB::connection('pgsql-cust')->select("call dcmg199iaigp51_updi ($statement) ");
        }
    }
}
