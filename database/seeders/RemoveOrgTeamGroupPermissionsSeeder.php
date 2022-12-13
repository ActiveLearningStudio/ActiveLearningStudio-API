<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RemoveOrgTeamGroupPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        return DB::transaction(function () {

            // Delete extra team permissions from organization.
            DB::delete("DELETE FROM
                            organization_role_permissions
                        WHERE
                            organization_role_type_id IN (
                                SELECT
                                    id
                                FROM
                                    organization_role_types
                            )
                            AND organization_permission_type_id IN (
                                SELECT
                                    id
                                FROM
                                    organization_permission_types
                                WHERE
                                    name LIKE 'team:%'
                                    AND name NOT IN (
                                        'team:view',
                                        'team:create',
                                        'team:edit',
                                        'team:delete'
                                    )
                            )");

            DB::delete("DELETE FROM
                            organization_permission_types
                        WHERE
                            name LIKE 'team:%'
                            AND name NOT IN (
                                'team:view',
                                'team:create',
                                'team:edit',
                                'team:delete'
                            )");

            // Delete extra group permissions from organization.
            DB::delete("DELETE FROM
                            organization_role_permissions
                        WHERE
                            organization_role_type_id IN (
                                SELECT
                                    id
                                FROM
                                    organization_role_types
                            )
                            AND organization_permission_type_id IN (
                                SELECT
                                    id
                                FROM
                                    organization_permission_types
                                WHERE
                                    name LIKE 'group:%'
                                    AND name NOT IN (
                                        'group:view',
                                        'group:create',
                                        'group:edit',
                                        'group:delete'
                                    )
                            )");

            DB::delete("DELETE FROM
                            organization_permission_types
                        WHERE
                            name LIKE 'group:%'
                            AND name NOT IN (
                                'group:view',
                                'group:create',
                                'group:edit',
                                'group:delete'
                            )");

            // Delete extra team permissions from teams permissions.
            DB::delete("DELETE FROM
                            team_role_permissions
                        WHERE
                            team_role_type_id IN (
                                SELECT
                                    id
                                FROM
                                    organization_role_types
                            )
                            AND team_permission_type_id IN (
                                SELECT
                                    id
                                FROM
                                    team_permission_types
                                WHERE name IN (
                                        'team:edit',
                                        'team:delete'
                                    )
                            )");

            DB::delete("DELETE FROM
                            team_permission_types
                        WHERE name IN (
                                   'team:edit',
                                   'team:delete'
                            )");

        });

    }
}
