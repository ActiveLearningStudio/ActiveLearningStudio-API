<?php

namespace App\Http\Controllers\Api\V1\CurrikiGo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Activity\ActivityRepositoryInterface;
use App\Repositories\LRSStatementsData\LRSStatementsDataRepositoryInterface;
use App\Repositories\LRSStatementsSummaryData\LRSStatementsSummaryDataRepositoryInterface;
use App\Services\LearnerRecordStoreService;
use Illuminate\Support\Facades\DB;
use App\CurrikiGo\LRS\InteractionFactory;

/**
 * @group 16. XAPI
 *
 * Cron job for XAPI extract
 */
class ExtractXAPIJSONController extends Controller
{
    /**
     * Runs the xAPI extract job script
     *
     * @param  ActivityRepositoryInterface  $activityRepository
     * @param  LRSStatementsDataRepositoryInterface  $lrsStatementsRepository
     * @param  LRSStatementsSummaryDataRepositoryInterface  $lrsStatementsSummaryDataRepositoryInterface
     * @return void
     */
    public function runJob(ActivityRepositoryInterface $activityRepository, LRSStatementsDataRepositoryInterface $lrsStatementsRepository, LRSStatementsSummaryDataRepositoryInterface $lrsStatementsSummaryDataRepositoryInterface)
    {
        $max_statement_id = $lrsStatementsRepository->findMaxByField('statement_id');
        if (!$max_statement_id) {
            $max_statement_id = 0;
        }
        \Log::info(date('Y-m-d h:i:s') . ' - Extract XAPI script - started from max ID: '. $max_statement_id);
        $offset = 0;
        $limit = config('xapi.lrs_job_row_limit');
        $xapiStatements = DB::connection('lrs_pgsql')->table(config('xapi.lrs_db_statements_table'))->select()
                ->offset($offset)
                ->limit($limit)
                ->where('voided', false)
                ->where('id', '>', $max_statement_id)
                ->orderby('id', 'ASC')
                ->get();

        try {
            $service = new LearnerRecordStoreService();
            foreach ($xapiStatements as $row) {
                $insertData = [];
                $statement = $service->buildStatementfromJSON($row->data);
                $actor = $statement->getActor();
                $target = $statement->getTarget();
                
                $verb = $service->getVerbFromStatement($statement->getVerb());
                $context = $statement->getContext();
                $nameOfActivity = 'Unknown Quiz set';
                $defaultActivityName = true;
                
                // At the moment, we're only tackling targets of 'activity' type.
                $definition = ($target->getObjectType() === 'Activity' ? $target->getDefinition() : '');
               
                // In some cases, we do not have a 'name' property for the object.
                // So, we've added an additional check here.
                // @todo - the LRS statements generated need to have this property
                if ($target->getObjectType() === 'Activity' && !empty($definition) && !$definition->getName()->isEmpty()) {
                    $nameOfActivity = $definition->getName()->getNegotiatedLanguageString();
                    $defaultActivityName = false;
                } elseif ($target->getObjectType() === 'StatementRef') {
                    $nameOfActivity = $target->getId();
                }
                
                $result = $statement->getResult();

                if (!empty($actor->getAccount())) {
                    $insertData['actor_id'] = $actor->getAccount()->getName();
                    $insertData['actor_homepage'] = $actor->getAccount()->getHomePage();
                } else {
                    // If will be mbox / name
                    $insertData['actor_id'] = $actor->getMbox();
                    $insertData['actor_homepage'] = $actor->getName();
                }
                
                $insertData['statement_id'] = $row->id;
                $insertData['statement_uuid'] = $statement->getId();
                
                $insertData['object_name'] = $nameOfActivity;
                $insertData['datetime'] = $row->created_at;
                $insertData['object_id'] = $target->getId();
                $insertData['verb'] = $verb;
                if (!empty($context)) {
                    $contextActivities = $context->getContextActivities();
                    $other = $contextActivities->getOther();
                    $groupingInfo = $service->findGroupingInfo($other);
                    $platform = $context->getPlatform();
                }
                
                // Skip if we don't have the activity.
                if (empty($groupingInfo['activity']) || empty($groupingInfo['class']) || empty($context)) {
                    // It maybe an old format statement. Just save verb, object and actor, and move on.
                    $inserted = $lrsStatementsRepository->create($insertData);
                    continue;
                }
                
                $activity = $activityRepository->find($groupingInfo['activity']);
                $activityId = null;
                $activityName = null;
                $projectId = null;
                $projectName = null;
                $playlistId = null;
                $playlistTitle = null;

                if ($activity) {
                    $activityId = $activity->id;
                    $activityName = $activity->title;
                    $project = $activity->playlist->project;
                    $projectId = $project->id;
                    $projectName = $project->name;
                    $playlistId = $activity->playlist_id;
                    $playlistTitle = $activity->playlist->title;
                }
                
                $category = $contextActivities->getCategory();
                $categoryId = '';
                $h5pInteraction = '';
                if (!empty($category)) {
                    $categoryId = end($category)->getId();
                    $h5pInteraction = explode("/", $categoryId);
                    $h5pInteraction = end($h5pInteraction);
                }

                $insertData['class_id'] = $groupingInfo['class'];
                $insertData['project_id'] = $projectId;
                $insertData['playlist_id'] = $playlistId;
                $insertData['assignment_submitted'] = ($verb === LearnerRecordStoreService::SUBMITTED_VERB_NAME ? TRUE : FALSE);
                
                $insertData['activity_category'] = $h5pInteraction;
                $insertData['platform'] = $platform;
                $insertData['project_name'] = $projectName;
                
                $insertData['playlist_name'] = $playlistTitle;
                $insertData['assignment_id'] = $activityId;
                $insertData['assignment_name'] = $activityName;

                //added submitted_id and attempt_id column for new summary page 
                $insertData['submission_id'] = $groupingInfo['submission'];
                $insertData['attempt_id'] = $groupingInfo['attempt'];
                // Extract information from object.definition.extensions
                if ($target->getObjectType() === 'Activity' && !empty($definition)) {
                    $glassAltCourseId = $service->getExtensionValueFromList($definition, LearnerRecordStoreService::EXTENSION_GCLASS_ALTERNATE_COURSE_ID);
                    $glassEnrollmentCode = $service->getExtensionValueFromList($definition, LearnerRecordStoreService::EXTENSION_GCLASS_ENROLLMENT_CODE);
                    $courseName = $service->getExtensionValueFromList($definition, LearnerRecordStoreService::EXTENSION_COURSE_NAME);

                    if (empty($glassAltCourseId)) {
                        $courseName = $service->getExtensionValueFromList($definition, LearnerRecordStoreService::EXTENSION_LMS_COURSE_NAME);
                        $glassAltCourseId = $service->getExtensionValueFromList($definition, LearnerRecordStoreService::EXTENSION_LMS_DOMAIN_URL);
                        $glassEnrollmentCode = $service->getExtensionValueFromList($definition, LearnerRecordStoreService::EXTENSION_LMS_COURSE_CODE);
                    }

                    $chapterName = $service->getExtensionValueFromList($definition, LearnerRecordStoreService::EXTENSION_H5P_CHAPTER_NAME);
                    $chapterIndex = $service->getExtensionValueFromList($definition, LearnerRecordStoreService::EXTENSION_H5P_CHAPTER_INDEX);
                    $referrer = $service->getExtensionValueFromList($definition, LearnerRecordStoreService::EXTENSION_REFERRER);
                    $insertData['glass_alternate_course_id'] = $glassAltCourseId;
                    $insertData['glass_enrollment_code'] = $glassEnrollmentCode;
                    $insertData['course_name'] = $courseName;
                    $insertData['chapter_name'] = (!empty($chapterName) ? $chapterName : 0);
                    $insertData['chapter_index'] = $chapterIndex;
                    $insertData['referrer'] = $referrer;
                }

                $interactionFactory = new InteractionFactory();
                $interaction = $interactionFactory->initInteraction($statement);
                $interactionSummaryGlobal = '';
                if ($interaction) {
                    $interactionSummary = $interaction->summary();
                    $interactionSummaryGlobal = $interactionSummary;
                    // Pull this from interaction...
                    $insertData['question'] = $interactionSummary['description'];
                    $insertData['duration'] = (!empty($interactionSummary['raw-duration']) ? $interactionSummary['raw-duration'] : null);
                    $insertData['options'] = (isset($interactionSummary['choices']) ? implode(", ", $interactionSummary['choices']) : null);
                    if (isset($interactionSummary['response']) && !empty($interactionSummary['response'])) { 
                        $insertData['answer'] = (is_array($interactionSummary['response']) ? implode(", ", $interactionSummary['response']) : $interactionSummary['response']);
                    }
                    if ($interactionSummary['scorable'] || (isset($interactionSummary['score']) && $interactionSummary['score']['max'] > 0)) {
                        $insertData['score_scaled'] = $interactionSummary['score']['scaled'];
                        $insertData['score_min'] = $interactionSummary['score']['min'];
                        $insertData['score_max'] = $interactionSummary['score']['max'];
                        $insertData['score_raw'] = $interactionSummary['score']['raw'];
                    }
                }
                // Overriding object name, when we have Questionnaire H5P, and object name is not available.
                if ($defaultActivityName && ($h5pInteraction 
                && in_array($insertData['verb'], ['completed', 'progressed']) && preg_match('/^H5P.Questionnaire/', $h5pInteraction, $matches))) {
                    $insertData['object_name'] = $matches[0];
                }
                // need to determine column layout interaction on 'completed'.
                if (($h5pInteraction 
                && in_array($insertData['verb'], ['completed', 'progressed']) && preg_match('/^H5P.Column/', $h5pInteraction))) {
                    $insertData['page'] = $insertData['object_name'];
                    $insertData['page_completed'] = $insertData['verb'] === 'completed' ? true : false;
                }
                $inserted = $lrsStatementsRepository->create($insertData);
               
                if ($inserted) {
                    //Capturing the custom verb "summary-curriki" for submit event with full summary rdbms.. 
                    if ($verb === 'summary-curriki' && !empty($interactionSummaryGlobal)) {
                        if (isset($interactionSummaryGlobal['response']) && !empty($interactionSummaryGlobal['response'])) {
                            $responseObject = (is_array(json_decode($interactionSummaryGlobal['response'], true)) ? json_decode($interactionSummaryGlobal['response'], true) : false);
                            if (!empty($responseObject)) {
                                foreach ($responseObject as $response) {
                                    $insertSummary = [];
                                    $insertSummary['statement_id'] = $insertData['statement_id'];
                                    $insertSummary['statement_uuid'] = $insertData['statement_uuid'];
                                    $insertSummary['actor_homepage'] = $insertData['actor_homepage'];
                                    $insertSummary['class_id'] = $insertData['class_id'];
                                    $insertSummary['assignment_name'] = $insertData['assignment_name'];
                                    $insertSummary['actor_id'] = $insertData['actor_id'];
                                    $insertSummary['page_name'] = $response['title'];
                                    $insertSummary['is_page_accessed'] = $response['accessed'];
                                    $insertSummary['is_event_interacted'] = $response['interacted'];
                                    $insertSummary['interacted_count'] = $response['interacted_count'];
                                    $insertSummary['total_interactions'] = $response['total_interactions'];
                                    $insertSummary['score'] = $response['score'];
                                    $lrsStatementsSummaryDataRepositoryInterface->create($insertSummary);

                                    \Log::info(date('Y-m-d h:i:s') . ' - RDBMS custom verb statement with statement id : ' . $insertData['statement_id'] . ' processed');
                                }
                            }
                        }
                    }
                }
            }
            \Log::info(date('Y-m-d h:i:s') . ' - Extract XAPI script ended');
            return 'Extract XAPI JSON Cron run successfully.';
        } catch (Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}
