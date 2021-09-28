<?php

use Illuminate\Database\Seeder;

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
                                        'team:edit',
                                        'team:create',
                                        'team:delete'
                                    )
                            )");

            DB::delete("DELETE FROM
                            organization_permission_types
                        WHERE
                            name LIKE 'team:%'
                            AND name NOT IN (
                                'team:view',
                                'team:edit',
                                'team:create',
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
                                        'group:edit',
                                        'group:create',
                                        'group:delete'
                                    )
                            )");

            DB::delete("DELETE FROM
                            organization_permission_types
                        WHERE
                            name LIKE 'group:%'
                            AND name NOT IN (
                                'group:view',
                                'group:edit',
                                'group:create',
                                'group:delete'
                            )");

        });

    }
}
