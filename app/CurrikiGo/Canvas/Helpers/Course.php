<?php

namespace App\CurrikiGo\Canvas\Helpers;

/**
 * A Helper class for searching entities
 */
class Course 
{
    /**
     * Get a course from a list
     * 
     * @param array $list A list of courses
     * @param string $name The course name to search in the list.
     * @return string|null
     */
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

    /**
     * Get a course from a list
     * 
     * @param array $list A list of courses
     * @param string $sisId Course SIS ID.
     * @return string|null
     */
    public static function getBySisId($list, $sisId)
    {
        $course = null;
        foreach ($list as $key => $item) {
            if (trim($item->sis_course_id) === trim($sisId)) {
                $course = $item;
                break;                
            }
        }
        return $course;
    }

    /**
     * Get a module from a list
     * 
     * @param array $list A list of courses
     * @param string $name The module name to search in the list.
     * @return string|null
     */
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
