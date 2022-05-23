<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCompositeUniqueIndexOnOrganizationRoleTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // this algorithm is using for to delete duplicated role types and their associated content
        return DB::transaction(function () {

            $organizations = DB::table('organizations')->pluck('id');
            $allDuplicatedRoleTypes = [];

            foreach ($organizations as $organization_id) {
                $duplicatedRoleTypes = DB::select("SELECT t1.id, t1.name
                                            FROM organization_role_types AS t1,
                                            organization_role_types AS t2
                                            WHERE t1.name = t2.name
                                            AND t1.id > t2.id
                                            AND t1.organization_id = " . $organization_id . "
                                            AND t2.organization_id = " . $organization_id . "
                                            ");

                if (count($duplicatedRoleTypes) > 0) {

                    foreach ($duplicatedRoleTypes as $duplicatedRoleType) {

                        $allDuplicatedRoleTypes[] = $duplicatedRoleType->id;

                        $originalRoleType = DB::select("SELECT id, name
                                                        FROM organization_role_types
                                                        WHERE organization_id = " . $organization_id . "
                                                        AND name = '" . $duplicatedRoleType->name . "'
                                                        ORDER BY id ASC limit 1
                                                    ");

                        // remove or assign user role to original role id
                        $userRoles = DB::table('organization_user_roles')
                                        ->where('organization_role_type_id', $duplicatedRoleType->id)
                                        ->get();

                        if (count($userRoles) > 0) {

                            foreach ($userRoles as $userRole) {

                                DB::delete("DELETE FROM organization_user_roles
                                        WHERE organization_role_type_id IN(
                                                                        " . $duplicatedRoleType->id . "
                                                                    )
                                        AND organization_id = " . $organization_id . "
                                    ");

                                DB::table("organization_user_roles")->insertOrIgnore(
                                    [
                                        'organization_id' => $organization_id,
                                        'user_id' => $userRole->user_id,
                                        'organization_role_type_id' => $originalRoleType[0]->id,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ]
                                );
                            }
                        }

                        // remove or assign role permissions to original role id
                        $rolePermissions = DB::table('organization_role_permissions')
                            ->where('organization_role_type_id', $duplicatedRoleType->id)
                            ->get();

                        if (count($rolePermissions) > 0) {

                            foreach ($rolePermissions as $rolePermission) {

                                DB::delete("DELETE FROM organization_role_permissions
                                        WHERE organization_role_type_id IN(
                                                                        " . $duplicatedRoleType->id . "
                                                                    )
                                    ");

                                DB::table("organization_role_permissions")->insertOrIgnore(
                                    [
                                        'organization_permission_type_id' => $rolePermission->organization_permission_type_id,
                                        'organization_role_type_id' => $originalRoleType[0]->id,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ]
                                );
                            }
                        }
                    }
                }
            }

            if (count($allDuplicatedRoleTypes) > 0) {
                DB::delete("DELETE FROM organization_role_types WHERE id IN(" . implode(",", $allDuplicatedRoleTypes) . ") ");
            }

            // apply unique checks on name and organization_id to avoid duplications
            Schema::table('organization_role_types', function (Blueprint $table) {
                $table->unique(['name', 'organization_id']);
            });

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organization_role_types', function (Blueprint $table) {
            $table->dropUnique(['name', 'organization_id']);
        });
    }
}
