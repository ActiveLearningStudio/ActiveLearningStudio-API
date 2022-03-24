<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterToolTypeColumnToLtiToolSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE lti_tool_settings DROP CONSTRAINT lti_tool_settings_tool_type_check");

        $types = ['kaltura', 'safari_montage', 'youtube', 'vimeo', 'other'];
        $result = join( ', ', array_map(function ($value){
            return sprintf("'%s'::character varying", $value);
        }, $types));

        DB::statement("ALTER TABLE lti_tool_settings ADD CONSTRAINT lti_tool_settings_tool_type_check CHECK (tool_type::text = ANY (ARRAY[$result]::text[]))");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
