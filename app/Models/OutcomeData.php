<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class OutcomeData extends Model
{
    /**
     * check the submitted statement is logged
     *
     * @var int
     * @var int
     * @var string
     * 
     */
    public static function isSubmitted($actor_id, $activity_id, $submission_id) 
    {
        $result = DB::select("select * from outcome_data where user_id = ? AND submission_id = ? AND assignment_id = ? AND verb= ? order by page_order desc", [$actor_id, $submission_id, $activity_id,'submitted-curriki']);
        if(count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * get the unique chapter names for grouping
     *
     * @var int
     * @var int
     * @var string
     * 
     */
    public static function getUniqueChapters($actor_id, $activity_id, $submission_id) 
    {
        $result = DB::select("select distinct chapter_name , page_order from outcome_data where user_id = ? AND submission_id = ?  AND assignment_id = ?  AND verb IN(?, ?)  order by page_order", [$actor_id, $submission_id, $activity_id, 'interacted', 'answered']);
        if (count($result) > 0) {
            $result = array_map(function($value) {
                return $value->chapter_name;
            }, $result);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * get the interacted and answered verb questions for activity
     *
     * @var int
     * @var int
     * @var string
     * 
     */
    public static function getOutcomeResults($actor_id, $activity_id, $submission_id) 
    {
        $result = DB::select("select * from outcome_data where user_id = ? AND submission_id = ?  AND assignment_id = ?  AND verb IN(?, ?) order by page_order,datetime asc", [$actor_id, $submission_id, $activity_id, 'interacted', 'answered']);
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }
}
