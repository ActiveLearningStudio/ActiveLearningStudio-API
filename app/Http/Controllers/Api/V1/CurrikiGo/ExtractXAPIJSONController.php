<?php

namespace App\Http\Controllers\Api\V1\CurrikiGo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Activity\ActivityRepositoryInterface;
use App\Repositories\LRSStatementsData\LRSStatementsDataRepositoryInterface;
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
     * Runs the extract job script
     *
     * @param  ActivityRepositoryInterface  $activityRepository
     * @param  LRSStatementsDataRepositoryInterface  $lrsStatementsRepository
     * @return void
     */
    public function runJob(ActivityRepositoryInterface $activityRepository, LRSStatementsDataRepositoryInterface $lrsStatementsRepository)
    {
        $max_statement_id = $lrsStatementsRepository->findMaxByField('statement_id');

        $offset = 0;
        $limit = config('xapi.lrs_job_row_limit');
        $xapiStatements = DB::connection('lrs_pgsql')->table(config('xapi.lrs_db_statements_table'))->select()
                ->offset($offset)
                ->limit($limit)
                ->where('voided', false)
                //->where('id', '>', $max_statement_id) //747 //738  //840 //794
                //->where('id', '<', )
                ->orderby('id', 'ASC')
                ->get();

        try {
            $service = new LearnerRecordStoreService();
            foreach ($xapiStatements as $row) {
                $insertData = [];
                echo '<pre>';
                print_r($row);
              //exit;
                $statement = $service->buildStatementfromJSON($row->data);
                $context = $statement->getContext();
                $contextActivities = $context->getContextActivities();
                $other = $contextActivities->getOther();
                $groupingInfo = $service->findGroupingInfo($other);
                $actor = $statement->getActor();
                $verb = $statement->getVerb()->getDisplay()->getNegotiatedLanguageString();
                $platform = $context->getPlatform();
                $target = $statement->getTarget();
                // Skip if we don't have the activity.
                if (empty($groupingInfo['activity'])) {
                    continue;
                }
                
                $nameOfActivity = 'Unknown Quiz set';
                $defaultActivityName = true;
                $definition = $target->getDefinition();
                // In some cases, we do not have a 'name' property for the object.
                // So, we've added an additional check here.
                // @todo - the LRS statements generated need to have this property
                if (!$definition->getName()->isEmpty()) {
                    $nameOfActivity = $definition->getName()->getNegotiatedLanguageString();
                    $defaultActivityName = false;
                }
                $result = $statement->getResult();
                
                // Get activity duration
                $extensions = $context->getExtensions();
                $endingPoint = $service->getEndingPointExtension($extensions);
                
                // this is basically the ending point (or the seek point) on the video where the quiz is set.
                // REMOVE THIS
                $groupingInfo['activity'] = 18045;
                $activity = $activityRepository->find($groupingInfo['activity']);
                if (!$activity) {
                    continue;
                }
               
                $activityId = $activity->id;
                $activityName = $activity->title;
                $project = $activity->playlist->project;
                $projectId = $project->id;
                $projectName = $project->name;
                $playlistId = $activity->playlist_id;
               
                $category = $contextActivities->getCategory();
                $categoryId = '';
                $h5pInteraction = '';
                if (!empty($category)) {
                    $categoryId = end($category)->getId();
                    $h5pInteraction = explode("/", $categoryId);
                    $h5pInteraction = end($h5pInteraction);
                }

                if (!empty($actor->getAccount())) {
                    $insertData['actor_id'] = $actor->getAccount()->getName();
                    $insertData['actor_homepage'] = $actor->getAccount()->getHomePage();
                }
                
                $insertData['statement_id'] = $row->id;
                $insertData['statement_uuid'] = $statement->getId();
                $insertData['class_id'] = $groupingInfo['class'];
                $insertData['object_name'] = $nameOfActivity;
                $insertData['datetime'] = $row->created_at;
                $insertData['object_id'] = $target->getId();
                $insertData['project_id'] = $projectId;
                $insertData['playlist_id'] = $playlistId;
                $insertData['assignment_submitted'] = ($verb === LearnerRecordStoreService::SUBMITTED_VERB_NAME ? TRUE : FALSE);
                
                $insertData['activity_category'] = $h5pInteraction;
                $insertData['verb'] = $verb;
                $insertData['platform'] = $platform;
                $insertData['project_name'] = $projectName;
                
                $insertData['playlist_name'] = $activity->playlist->title;
                $insertData['assignment_id'] = $activityId;
                $insertData['assignment_name'] = $activityName;

                $interactionFactory = new InteractionFactory();
                $interaction = $interactionFactory->initInteraction($statement);
                if ($interaction) {
                    //print_r($interaction);
                    $interactionSummary = $interaction->summary();
                    echo 'Interaction Summary:';
                    print_r($interactionSummary);
                    
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
                
                //print_r($statement);
                print_r($insertData);
                exit;
                $inserted = $lrsStatementsRepository->create($insertData);
                if ($inserted) {
                    echo 'Inserted'. $insertData['statement_id'];
                    //print_r($inserted);

                }
                //exit;
                //\Log::info('this is new statement: ', print_r($statement, true));
                
                //exit;
            }
            exit('statements inserted');
            \Log::info('this is new statement: ', print_r($statement, true));
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
            \Log::error($e->getMessage());
        }
        
    }
}
