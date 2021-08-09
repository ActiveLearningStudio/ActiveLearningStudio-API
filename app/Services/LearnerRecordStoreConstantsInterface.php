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
     * H5P xAPI subContent ID
     *
     * @var string
     */
    const EXTENSION_H5P_LOCAL_CONTENT_ID = 'http://h5p.org/x-api/h5p-local-content-id';

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
    const SKIPPED_VERB_ID = 'http://adlnet.gov/expapi/verbs/skipped';

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
     * Sumamry-Curriki verb id for XAPI statements
     *
     * @var string
     */
    const SUMMARY_CURRIKI_VERB_ID = 'http://adlnet.gov/expapi/verbs/summary-curriki';

    /**
     * Assignment Submitted Verb Name
     *
     * @var string
     */
    const SUBMITTED_VERB_NAME = 'submitted-curriki';

    /**
     * Google Classroom Alternate Course ID
     *
     * @var string
     */
    const EXTENSION_GCLASS_ALTERNATE_COURSE_ID = 'http://currikistudio.org/x-api/gclass-alternate-course-id';

    /**
     * Google Classroom Enrollment Code
     *
     * @var string
     */
    const EXTENSION_GCLASS_ENROLLMENT_CODE = 'http://currikistudio.org/x-api/gclass-enrollment-code';

    /**
     * LMS Course Name
     *
     * @var string
     */
    const EXTENSION_LMS_COURSE_NAME = 'http://currikistudio.org/x-api/lms-course-name';

    /**
     * LMS Api Domain URL
     *
     * @var string
     */
    const EXTENSION_LMS_DOMAIN_URL = 'http://currikistudio.org/x-api/lms-domain-url';

    /**
     * LMS Course Code
     *
     * @var string
     */
    const EXTENSION_LMS_COURSE_CODE = 'http://currikistudio.org/x-api/lms-course-code';

    /**
     * Course Name (Google Classroom / LMS)
     *
     * @var string
     */
    const EXTENSION_COURSE_NAME = 'http://currikistudio.org/x-api/course-name';

    /**
     * H5P Chapter/Slide/Page Name
     *
     * @var string
     */
    const EXTENSION_H5P_CHAPTER_NAME = 'http://currikistudio.org/x-api/h5p-chapter-name';

    /**
     * H5P Chapter/Slide/Page Index
     *
     * @var string
     */
    const EXTENSION_H5P_CHAPTER_INDEX = 'http://currikistudio.org/x-api/h5p-chapter-index';

    /**
     * Shared Activity Referrer
     *
     * @var string
     */
    const EXTENSION_REFERRER = 'http://id.tincanapi.com/extension/referrer';

}
