<?php

namespace Database\Seeders;

use App\Models\UiModulePermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UiModuleOrganizationPermissionMappingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $uiBackendPermissionMapping = [
            "Organizations" => [
                "Organization" => [
                    "View" => [
                        'organization:view'
                    ],
                    "Edit" => [
                        'organization:view',
                        'organization:edit',
                        'organization:delete',
                        'organization:create'
                    ],
                    "None" => []
                ]
            ],
            "Projects" => [
                "All Projects" => [
                    "View" => [
                        'organization:view-all-project'
                    ],
                    "Edit" => [
                        'organization:view-all-project',
                        'organization:upload-thumb',
                        'organization:edit-project',
                        'organization:delete-project',
                        'organization:view-library-request-project',
                        'organization:review-library-request-project'
                    ],
                    "None" => []
                ],
                "Import/Export Projects" => [
                    "View" => [
                        'organization:view-exported-project'
                    ],
                    "Edit" => [
                        'organization:view-exported-project',
                        'organization:export-project',
                        'organization:import-project',
                        'organization:download-project'
                    ],
                    "None" => []
                ]
            ],
            "Ref. Tables" => [
                "Activity Types" => [
                    "View" => [
                        'organization:view-activity-type'
                    ],
                    "Edit" => [
                        'organization:view-activity-type',
                        'organization:create-activity-type',
                        'organization:delete-activity-type',
                        'organization:edit-activity-type'
                    ],
                    "None" => []
                ],
                "Activity Items" => [
                    "View" => [
                        'organization:view-activity-item'
                    ],
                    "Edit" => [
                        'organization:view-activity-item',
                        'organization:create-activity-item',
                        'organization:delete-activity-item',
                        'organization:edit-activity-item'
                    ],
                    "None" => []
                ],
                "Activity Layouts" => [
                    "View" => [
                        'organization:view-activity-layout'
                    ],
                    "Edit" => [
                        'organization:view-activity-layout',
                        'organization:create-activity-layout',
                        'organization:delete-activity-layout',
                        'organization:edit-activity-layout'
                    ],
                    "None" => []
                ],
                "Subjects" => [
                    "View" => [
                        'organization:view-subject'
                    ],
                    "Edit" => [
                        'organization:view-subject',
                        'organization:create-subject',
                        'organization:delete-subject',
                        'organization:edit-subject'
                    ],
                    "None" => []
                ],
                "Education Levels" => [
                    "View" => [
                        'organization:view-education-level'
                    ],
                    "Edit" => [
                        'organization:view-education-level',
                        'organization:create-education-level',
                        'organization:delete-education-level',
                        'organization:edit-education-level'
                    ],
                    "None" => []
                ],
                "Author Tags" => [
                    "View" => [
                        'organization:view-author-tag'
                    ],
                    "Edit" => [
                        'organization:view-author-tag',
                        'organization:create-author-tag',
                        'organization:delete-author-tag',
                        'organization:edit-author-tag'
                    ],
                    "None" => []
                ]
            ],
            "Integrations" => [
                "LMS Settings" => [
                    "View" => [
                        'organization:view-lms-setting'
                    ],
                    "Edit" => [
                        'organization:view-lms-setting',
                        'organization:create-lms-setting',
                        'organization:delete-lms-setting',
                        'organization:edit-lms-setting'
                    ],
                    "None" => []
                ],
                "LTI Tools" => [
                    "View" => [
                        'organization:view-all-setting'
                    ],
                    "Edit" => [
                        'organization:view-all-setting',
                        'organization:create-all-setting',
                        'organization:delete-all-setting',
                        'organization:edit-all-setting'
                    ],
                    "None" => []
                ],
                "BrightCove" => [
                    "View" => [
                        'organization:view-brightcove-setting'
                    ],
                    "Edit" => [
                        'organization:view-brightcove-setting',
                        'organization:create-brightcove-setting',
                        'organization:delete-brightcove-setting',
                        'organization:edit-brightcove-setting'
                    ],
                    "None" => []
                ],
                "Media" => [
                    "View" => [
                        'organization:view-media'
                    ],
                    "Edit" => [
                        'organization:view-media',
                        'organization:create-media',
                        'organization:delete-media',
                        'organization:edit-media'
                    ],
                    "None" => []
                ],
                "Google Classroom" => [
                    "View" => [
                        'organization:view-google-classroom'
                    ],
                    "Edit" => [
                        'organization:view-google-classroom',
                        'organization:create-google-classroom',
                        'organization:delete-google-classroom',
                        'organization:edit-google-classroom'
                    ],
                    "None" => []
                ],
                "Microsoft Teams" => [
                    "View" => [
                        'organization:view-microsoft-team'
                    ],
                    "Edit" => [
                        'organization:view-microsoft-team',
                        'organization:create-microsoft-team',
                        'organization:delete-microsoft-team',
                        'organization:edit-microsoft-team'
                    ],
                    "None" => []
                ]
            ],
            "Users" => [
                "Manage Users" => [
                    "View" => [
                        'organization:view-user'
                    ],
                    "Edit" => [
                        'organization:view-user',
                        'organization:add-user',
                        'organization:invite-members',
                        'organization:update-user',
                        'organization:delete-user',
                        'organization:add-admin',
                        'organization:delete-admin',
                        'organization:remove-user'
                    ],
                    "None" => []
                ],
                "Manage Roles" => [
                    "View" => [
                        'organization:view-role'
                    ],
                    "Edit" => [
                        'organization:view-role',
                        'organization:add-role',
                        'organization:edit-role'
                    ],
                    "None" => []
                ]
            ],
            "Independent Activities" => [
                "All Independent Activities" => [
                    "View" => [
                        'independent-activity:view'
                    ],
                    "Edit" => [
                        'independent-activity:view',
                        'independent-activity:create',
                        'independent-activity:edit',
                        'independent-activity:delete',
                        'independent-activity:share',
                        'independent-activity:duplicate'
                    ],
                    "None" => []
                ],
                "Export/Import Activities" => [
                    "View" => [
                        'independent-activity:view-export'
                    ],
                    "Edit" => [
                        'independent-activity:view-export',
                        'independent-activity:export',
                        'independent-activity:import'
                    ],
                    "None" => []
                ]
            ],
            "Authoring" => [
                "Project" => [
                    "View" => [
                        'project:view'
                    ],
                    "Edit" => [
                        'project:view',
                        'project:share',
                        'project:clone',
                        'project:edit',
                        'project:delete',
                        'project:create',
                        'project:request-indexing',
                        'project:favorite',
                        'project:publish',
                        'project:upload-thumb',
                        'project:recent'
                    ],
                    "None" => []
                ],
                "Playlist" => [
                    "View" => [
                        'playlist:view'
                    ],
                    "Edit" => [
                        'playlist:view',
                        'playlist:publish',
                        'playlist:duplicate',
                        'playlist:edit',
                        'playlist:delete',
                        'playlist:create'
                    ],
                    "None" => []
                ],
                "Activity" => [
                    "View" => [
                        'activity:view'
                    ],
                    "Edit" => [
                        'activity:view',
                        'activity:share',
                        'activity:duplicate',
                        'activity:edit',
                        'activity:delete',
                        'activity:create',
                        'activity:upload'
                    ],
                    "None" => []
                ],
                "Team" => [
                    "View" => [
                        'team:view'
                    ],
                    "Edit" => [
                        'team:view',
                        'team:create',
                        'team:edit',
                        'team:delete'
                    ],
                    "None" => []
                ],
                "Independent Activity" => [
                    "View" => [
                        'independent-activity:view-author'
                    ],
                    "Edit" => [
                        'independent-activity:view-author',
                        'independent-activity:edit-author'
                    ],
                    "None" => []
                ],
                "My Interactive Video" => [
                    "View" => [
                        'video:view'
                    ],
                    "None" => []
                ]
            ]
        ];

        DB::transaction(function () use ($uiBackendPermissionMapping) {

            DB::table('ui_modules')->where('title', 'Organiziations')->update(['title' => 'Organizations']);
            DB::table('ui_modules')->where('title', 'Organiziation')->update(['title' => 'Organization']);

            foreach ($uiBackendPermissionMapping as $moduleTitle => $subModules) {
                DB::table('ui_modules')
                    ->updateOrInsert(
                        [
                            'title' => $moduleTitle
                        ],
                        [
                            'created_at' => 'NOW()'
                        ]
                    );

                $moduleObj = DB::table('ui_modules')->where('title', $moduleTitle)->first();

                foreach ($subModules as $subModuleTitle => $uiPermissions) {
                    DB::table('ui_modules')
                        ->updateOrInsert(
                            [
                                'title' => $subModuleTitle,
                                'parent_id' => $moduleObj->id
                            ],
                            [
                                'created_at' => 'NOW()'
                            ]
                        );

                    $subModuleObj = DB::table('ui_modules')
                                        ->where('title', $subModuleTitle)
                                        ->first();

                    foreach ($uiPermissions as $uiPermissionTitle => $backendPermissions) {
                        DB::table('ui_module_permissions')
                            ->updateOrInsert(
                                [
                                    'title' => $uiPermissionTitle,
                                    'ui_module_id' => $subModuleObj->id
                                ],
                                [
                                    'created_at' => 'NOW()'
                                ]
                            );

                        if ($backendPermissions) {
                            $uiPermissionObj = UiModulePermission::where('title', $uiPermissionTitle)
                                                                   ->where('ui_module_id', $subModuleObj->id)
                                                                   ->first();

                            $backendPermissionIds = DB::table('organization_permission_types')
                                                        ->whereIn('name', $backendPermissions)
                                                        ->pluck('id')
                                                        ->toArray();

                            $uiPermissionObj->organizationPermissionTypes()->sync($backendPermissionIds);
                        }
                    }
                }
            }
        });
    }
}
