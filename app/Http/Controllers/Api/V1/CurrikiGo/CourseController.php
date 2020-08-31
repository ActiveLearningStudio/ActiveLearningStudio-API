<?php
namespace App\Http\Controllers\Api\V1\CurrikiGo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CurrikiGo\Moodle\Course as MoodleCourse;
use App\CurrikiGo\Canvas\Course as CanvasCourse;
use Validator;
use App\Models\Project;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepository;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use App\CurrikiGo\Canvas\Client;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

/**
 * @group  CurrikiGo
 * @authenticated
 * 
 * APIs for fetching courses from LMSs
 */
class CourseController extends Controller
{
    private $lmsSettingRepository;

    /**
     * CourseController constructor.
     *
     * @param LmsSettingRepositoryInterface $lmsSettingRepository
     */
    public function __construct(LmsSettingRepositoryInterface $lmsSettingRepository)
    {
        $this->lmsSettingRepository = $lmsSettingRepository;
    }

    /**
	 * Fetch a course from Canvas
     * 
     * @urlParam  project required The ID of the project.
     * @bodyParam  setting_id int The id of the LMS setting.
     * 
     * @responseFile  responses/fetchfromcanvas.post.json
     * @response  400 {
     *  "errors": "Validation error"
     * }
     * @response  403 {
     *  "errors": "You are not authorized to perform this action."
     * }
	 */
    public function fetchFromCanvas(Project $project, Request $request)
    {                
        $authenticated_user = auth()->user();
        if (Gate::forUser($authenticated_user)->allows('fetch-lms-course', $project)) {
            $validator = Validator::make($request->all(), ['setting_id' => 'required']);
            
            if ($validator->fails()) {
                $messages = $validator->messages();
                return response([
                    'errors' => [$messages],
                ], 400);
            }
            $lmsSettings = $this->lmsSettingRepository->find($request->input('setting_id'));
            $canvas_client = new Client($lmsSettings);
            $canvas_course = new CanvasCourse($canvas_client);
            $outcome = $canvas_course->fetch($project);

            return response([
                'project' => $outcome,
            ], 200);
        }

        return response([
            'errors' => ['You are not authorized to perform this action.'],
        ], 403);
    }

    public function fetchFromMoodle(Request $request)
    {                

        $validator = Validator::make($request->all(), ['setting_id' => 'required', 'project_id'=> 'required']);

        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(['error' => $messages]);
        }
        
        $moodle_course = new MoodleCourse($request->input("setting_id"));
        $response = $moodle_course->fetch(['project_id' => $request->input("project_id")]);
        if(is_null($response)){
            return responseError(null,"No Project Found");
        }
        
        $code = $response->getStatusCode();
        if($code == 200){
            $outcome = $response->getBody()->getContents();
            return responseSuccess( json_decode($outcome) );
        }else {
            return responseError(null,"Moodle API Request Failed");
        }
    }

}
