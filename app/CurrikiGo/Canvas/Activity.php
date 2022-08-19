<?php

namespace App\CurrikiGo\Canvas;

use App\CurrikiGo\Canvas\Client;
use App\CurrikiGo\Canvas\Commands\GetUsersCommand;
use App\CurrikiGo\Canvas\Commands\GetEnrollmentsCommand;
use App\CurrikiGo\Canvas\Commands\GetCoursesCommand;
use App\CurrikiGo\Canvas\Commands\CreateCourseCommand;
use App\CurrikiGo\Canvas\Commands\GetModulesCommand;
use App\CurrikiGo\Canvas\Commands\CreateModuleCommand;
use App\CurrikiGo\Canvas\Commands\CreateModuleItemCommand;
use App\CurrikiGo\Canvas\Commands\GetCourseEnrollmentCommand;
use App\CurrikiGo\Canvas\Commands\CreateCourseEnrollmentCommand;
use App\CurrikiGo\Canvas\Helpers\Course as CourseHelper;
use App\CurrikiGo\Canvas\Helpers\Enrollment as EnrollmentHelper;
use App\Models\Activity as ActivityModel;
use App\Http\Resources\V1\CurrikiGo\CanvasPlaylistResource;
use Illuminate\Support\Facades\Auth;

/**
 * Activity class for handling Activity publishing to Canvas LMS
 */
class Activity
{
    /**
     * Canvas Client instance
     * 
     * @var \App\CurrikiGo\Canvas\Client
     */
    private $canvasClient;
    
    /**
     * Make an instance of the class
     * 
     * @param \App\CurrikiGo\Canvas\Client $client
     */
    public function __construct(Client $client)
    {
        $this->canvasClient = $client;
    }

    /**
     * Send an Activity to Canvas LMS
     * 
     * @param \App\Models\Activity $activity
     * @param array $data 
     * @return array
     */
    public function send(ActivityModel $activity, $data)
    {
        
    }
}
