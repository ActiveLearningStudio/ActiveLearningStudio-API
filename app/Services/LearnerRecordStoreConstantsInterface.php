<?php

namespace App\Services;

/**
 * Interface for Learner Record Store constants
 */
interface LearnerRecordStoreConstantsInterface
{
    /**
     * Ending-point extension IRI
     * 
     * @var string
     */
    const EXTENSION_ENDING_POINT_IRI = 'http://id.tincanapi.com/extension/ending-point';

    /**
     * H5P xAPI subContent ID 
     * 
     * @var string
     */
    const EXTENSION_H5P_SUBCONTENT_ID = 'http://h5p.org/x-api/h5p-subContentId';
    
    /**
     * Answered verb id for XAPI statements
     * 
     * @var string
     */
    const ANSWERED_VERB_ID = 'http://adlnet.gov/expapi/verbs/answered';

    /**
     * Completed verb id for XAPI statements
     * 
     * @var string
     */
    const COMPLETED_VERB_ID = 'http://adlnet.gov/expapi/verbs/completed';

    /**
     * Skipped verb id for XAPI statements
     * 
     * @var string
     */
    const SKIPPED_VERB_ID = 'http://id.tincanapi.com/verb/skipped';

    /**
     * Attempted verb id for XAPI statements
     * 
     * @var string
     */
    const ATTEMPTED_VERB_ID = 'http://adlnet.gov/expapi/verbs/attempted';

    /**
     * Interacted verb id for XAPI statements
     * 
     * @var string
     */
    const INTERACTED_VERB_ID = 'http://adlnet.gov/expapi/verbs/interacted';

    /**
     * Submitted-Curriki verb id for XAPI statements
     * 
     * @var string
     */
    const SUBMITTED_CURRIKI_VERB_ID = 'http://adlnet.gov/expapi/verbs/submitted-curriki';

    /**
     * Assignment Submitted Verb Name
     * 
     * @var string
     */
    const SUBMITTED_VERB_NAME = 'submitted-curriki';

}
