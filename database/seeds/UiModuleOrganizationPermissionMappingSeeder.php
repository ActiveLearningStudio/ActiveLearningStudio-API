<?php

use Illuminate\Database\Seeder;

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
            "Organiziations" => [
                "Organiziation" => [
                    "View" => [
                        'organization:view'
                    ],
                    "Edit" => [
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
                        'organization:create-activity-item',
                        'organization:delete-activity-item',
                        'organization:edit-activity-item'
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
                        'organization:create-brightcove-setting',
                        'organization:delete-brightcove-setting',
                        'organization:edit-brightcove-setting'
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
                        'independent-activity:export',
                        'independent-activity:import'
                    ],
                    "None" => []
                ]
            ],
            "Authoring" => [
                "Project" => [
                    "View" => [
                        'project:view',
                        'project:share',
                        'project:clone'
                    ],
                    "Edit" => [
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
                        'playlist:view',
                        'playlist:publish',
                        'playlist:duplicate'
                    ],
                    "Edit" => [
                        'playlist:edit',
                        'playlist:delete',
                        'playlist:create'
                    ],
                    "None" => []
                ],
                "Activity" => [
                    "View" => [
                        'activity:view',
                        'activity:share',
                        'activity:duplicate'
                    ],
                    "Edit" => [
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

                $moduleObj = DB::table('ui_modules')
                ->where('title', $moduleTitle)
                ->first();

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
                            $uiPermissionObj = DB::table('ui_module_permissions')
                            ->where([
                                [
                                    'title', '=', $uiPermissionTitle
                                ],
                                [
                                    'ui_module_id', '=', $subModuleObj->id
                                ]
                            ])
                            ->first();

                            $backendPermissionIds = DB::table('organization_permission_types')
                            ->whereIn('name', $backendPermissions)
                            ->pluck('id')
                            ->toArray();

                            foreach ($backendPermissionIds as $backendPermissionId) {
                                DB::table('ui_organization_permission_mappings')
                                ->insertOrIgnore([
                                    'ui_module_permission_id' => $uiPermissionObj->id,
                                    'organization_permission_type_id' => $backendPermissionId,
                                    'created_at' => 'NOW()'
                                ]);
                            }
                        }
                    }
                }
            }
        });
    }
}
