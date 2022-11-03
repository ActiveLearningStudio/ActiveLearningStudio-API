<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutcomeDataView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("drop view IF EXISTS outcome_data");

        DB::statement("CREATE OR REPLACE VIEW public.outcome_data
AS SELECT sq1.user_id,
    sq1.class_id,
    sq1.project_id,
    lsd.project_name,
    sq1.playlist_id,
    lsd.playlist_name,
    sq1.assignment_id,
    lsd.assignment_name,
    sq1.submission_id,
    sq1.question,
    lsd.options,
    replace(lsd.answer, 'Not at like me'::text, 'Not at all like me'::text) AS answer,
    lsd.verb,
    lsd.duration,
    lsd.score_scaled,
    lsd.score_min,
    lsd.score_max,
    lsd.score_raw,
    lsd.activity_category,
    lsd.chapter_name,
    sq1.object_id,
    lsd.object_name,
    lm.page_order,
    lsd.glass_alternate_course_id,
    lsd.course_name AS grade_level,
    sq1.datetime
    FROM ( SELECT lrs_statements_data.actor_id AS user_id,
            lrs_statements_data.class_id,
            lrs_statements_data.project_id,
            lrs_statements_data.playlist_id,
            lrs_statements_data.assignment_id,
            lrs_statements_data.submission_id,
            lrs_statements_data.object_id,
            replace(lrs_statements_data.question, '  '::text, ' '::text) AS question,
            max(lrs_statements_data.datetime) AS datetime
            FROM lrs_statements_data
            WHERE lrs_statements_data.question IS NOT NULL AND lrs_statements_data.answer IS NOT NULL AND (lrs_statements_data.verb::text = ANY (ARRAY['answered'::text, 'interacted'::text, 'completed'::text]))
            GROUP BY lrs_statements_data.actor_id, lrs_statements_data.class_id, lrs_statements_data.project_id, lrs_statements_data.playlist_id, lrs_statements_data.assignment_id, lrs_statements_data.submission_id, lrs_statements_data.object_id, (replace(lrs_statements_data.question, '  '::text, ' '::text))) sq1
        LEFT JOIN lrs_statements_data lsd ON sq1.user_id::text = lsd.actor_id::text AND sq1.class_id = lsd.class_id AND sq1.assignment_id::text = lsd.assignment_id::text AND sq1.submission_id::text = lsd.submission_id::text AND sq1.object_id::text = lsd.object_id::text AND replace(sq1.question, '  '::text, ' '::text) = replace(lsd.question, '  '::text, ' '::text) AND sq1.datetime = lsd.datetime
        LEFT JOIN lrs_metadata lm ON lsd.assignment_id::text = lm.activity_id::text and lm.chapter_name=lsd.chapter_name
UNION ALL
    SELECT sq1.user_id,
    sq1.class_id,
    lsd.project_id,
    sq1.project_name,
    lsd.playlist_id,
    sq1.playlist_name,
    lsd.assignment_id,
    sq1.assignment_name,
    sq1.submission_id,
    replace(lsd.question, '  '::text, ' '::text) AS question,
    lsd.options,
    replace(lsd.answer, 'Not at like me'::text, 'Not at all like me'::text) AS answer,
    lsd.verb,
    lsd.duration,
    lsd.score_scaled,
    lsd.score_min,
    lsd.score_max,
    lsd.score_raw,
    lsd.activity_category,
    lsd.chapter_name,
    lsd.object_id,
    lsd.object_name,
    50 AS page_order,
    lsd.glass_alternate_course_id,
    lsd.course_name AS grade_level,
    sq1.datetime
    FROM ( SELECT lrs_statements_data.actor_id AS user_id,
            lrs_statements_data.class_id,
            lrs_statements_data.project_name,
            lrs_statements_data.playlist_name,
            lrs_statements_data.assignment_name,
            lrs_statements_data.submission_id,
            max(lrs_statements_data.datetime) AS datetime
            FROM lrs_statements_data
            WHERE lrs_statements_data.verb::text = 'submitted-curriki'::text
            GROUP BY lrs_statements_data.actor_id, lrs_statements_data.class_id, lrs_statements_data.project_name, lrs_statements_data.playlist_name, lrs_statements_data.assignment_name, lrs_statements_data.submission_id) sq1
        LEFT JOIN lrs_statements_data lsd ON sq1.user_id::text = lsd.actor_id::text AND sq1.class_id = lsd.class_id AND sq1.assignment_name::text = lsd.assignment_name::text AND sq1.submission_id::text = lsd.submission_id::text AND sq1.datetime = lsd.datetime
    WHERE lsd.verb::text = 'submitted-curriki'::text");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW outcome_data");
    }
}
