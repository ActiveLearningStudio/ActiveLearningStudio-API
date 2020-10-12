<?php

namespace App\CurrikiGo\Canvas\Helpers;

/**
 * A Helper class for Enrollment
 */
class Enrollment 
{
    /**
     * Checkk Enrollment
     * 
     * @param array $loginId LMS Login Id
     * @param array $enrollments enrol.
     * @return bool
     */
    public static function isEnrolled($loginId, $enrollments)
    {
        $isEnroll = false;
        foreach ($enrollments as $enrollment) {
            if ($enrollment->user->login_id === trim($loginId)) {
                $isEnroll = true;
                break;
            }
        }
        return $isEnroll;
    }
}
