<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateLTIToolSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// For master demo user on dev
    	$user = DB::table('users')->where('id', 2)->first();
    	if ($user) {
    		$ltiToolSettings = [
	            [
	                'user_id' => 2,
			        'organization_id' => 1,
			        'tool_name' => 'Safari Montage',
			        'tool_url' => 'https://partner.safarimontage.com/SAFARI/api/imsltisearch.php',
			        'tool_domain' => 'partner.safarimontage.com',
			        'lti_version' => 'LTI-1p0',
			        'tool_type' => 'safari_montage',
			        'tool_consumer_key' => '2b9d3d6f-a46f-11eb-915b-001e67972a31.partner.safarimontage.com',
			        'tool_secret_key' => '4dbe1fa5c24b0cb35bc33097393d70a329c14933',
			        'tool_description' => 'Testing safari montage as LTI Tool Consumer',
			        'tool_custom_parameter' => "launch_frame='iframe', iframe='embed'",
			        'tool_content_selection_url' => 'https://partner.safarimontage.com/SAFARI/api/imsltisearchdd.php'
	            ],
	            [
	                'user_id' => 2,
			        'organization_id' => 1,
			        'tool_name' => 'Kaltura Integration',
			        'tool_url' => 'https://4186473.kaf.kaltura.com',
			        'tool_domain' => '4186473.kaf.kaltura.com',
			        'lti_version' => 'LTI-1p0',
			        'tool_type' => 'kaltura',
			        'tool_consumer_key' => '4186473',
			        'tool_secret_key' => '69fe402f3429ede6dbf4b4f928767bf6',
			        'tool_description' => 'Testing kaltura integration',
			        'tool_content_selection_url' => 'https://4186473.kaf.kaltura.com'
	            ]
	        ];

	        DB::table('lti_tool_settings')->truncate();
	        foreach ($ltiToolSettings as $lts) {
	            DB::table('lti_tool_settings')->insert($lts);
	        }
    	}    	
    }
}
