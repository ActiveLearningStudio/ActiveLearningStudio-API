<?php

namespace App\CurrikiGo\Canvas\Helpers;

class Course 
{
    public static function getByName($list, $name)
    {
        $course = null;
        foreach ($list as $key => $item) {
            if (trim($item->name) === trim($name)) {
                $course = $item;
                break;                
            }
        }
        return $course;
    }

    public static function getModuleByName($list, $name)
    {
        $module = null;
        foreach ($list as $key => $entity) {
            if (trim($entity->name) === trim($name)) {
                $module = $entity;
                break;                
            }
        }
        return $module;
    }
}
