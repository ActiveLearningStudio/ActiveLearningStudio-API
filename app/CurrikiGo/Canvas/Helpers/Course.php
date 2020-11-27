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
            $pos = strpos(trim($item->sis_course_id), trim($sisId));
            if ($pos !== false) {
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

    /**
     * Create human-friendly URL string
     * 
     * @param string $str Input string
     * @param string $separator Word separator (usually '-' or '_') 
     * @param bool $lowercase Whether to transform the output string to lowercase
     * @return string|null
     */
    public static function urlTitle(string $str, string $separator = '-', bool $lowercase = true): string
    {
        $qSeparator = preg_quote($separator, '#');

        $trans = [
            '&.+?;' => '',
            '[^\w\d _-]' => '',
            '\s+' => $separator,
            '(' . $qSeparator . ')+' => $separator,
        ];

        $str = strip_tags($str);
        foreach ($trans as $key => $val) {
            $str = preg_replace('#' . $key . '#iu', $val, $str);
        }

        if ($lowercase === true) {
            $str = mb_strtolower($str);
        }

        return trim(trim($str, $separator));
    }
}
