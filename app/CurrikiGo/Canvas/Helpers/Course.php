<?php
namespace App\CurrikiGo\Canvas\Helpers;

class Course 
{
    public static function getByName($list, $name){
        $course_obj = null;
        foreach ($list as $key => $course) {
            if (trim($course->name) == trim($name)) {
                $course_obj = $course;                
            }
        }

        return $course_obj;
    }

    public static function getModulesByName($list, $name){
        $entity_obj = null;
        foreach ($list as $key => $entity) {
            if (trim($entity->name) == trim($name)) {
                $entity_obj = $entity;                
            }
        }

        return $entity_obj;
    }


}
