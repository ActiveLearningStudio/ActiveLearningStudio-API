<?php

namespace Database\Seeders;

use App\Models\OrganizationRoleType;
use App\Repositories\UiOrganizationPermissionMapping\UiOrganizationPermissionMappingRepositoryInterface;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationRoleUiPermissionSeeder extends Seeder
{
    public $uiModules;
    public $uiModulePermissions;
    public $domain;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminUiPermissions = [
            "Organization" => "Edit",
            "All Independent Activities" => "Edit",
            "Export/Import Activities" => "Edit",
            "All Projects" => "Edit",
            "Import/Export Projects" => "Edit",
            "Activity Types" => "Edit",
            "Activity Items" => "Edit",
            "Activity Layouts" => "Edit",
            "Subjects" => "Edit",
            "Education Levels" => "Edit",
            "Author Tags" => "Edit",
            "Manage Users" => "Edit",
            "Manage Roles" => "Edit",
            "LMS Settings" => "Edit",
            "LTI Tools" => "Edit",
            "BrightCove" => "Edit",
            "Media" => "Edit",
            "Google Classroom" => "Edit",
            "Microsoft Teams" => "Edit",
            "Project" => "Edit",
            "Playlist" => "Edit",
            "Activity" => "Edit",
            "Team" => "Edit",
            "Independent Activity" => "Edit",
            "My Interactive Video" => "None"
        ];

        $courseCreatorUiPermissions = [
            "Organization" => "None",
            "All Independent Activities" => "None",
            "Export/Import Activities" => "None",
            "All Projects" => "None",
            "Import/Export Projects" => "None",
            "Activity Types" => "None",
            "Activity Items" => "None",
            "Activity Layouts" => "None",
            "Subjects" => "None",
            "Education Levels" => "None",
            "Author Tags" => "None",
            "Manage Users" => "None",
            "Manage Roles" => "None",
            "LMS Settings" => "None",
            "LTI Tools" => "None",
            "BrightCove" => "None",
            "Media" => "None",
            "Google Classroom" => "None",
            "Microsoft Teams" => "None",
            "Project" => "Edit",
            "Playlist" => "Edit",
            "Activity" => "Edit",
            "Team" => "Edit",
            "Independent Activity" => "Edit",
            "My Interactive Video" => "None"
        ];

        $memberUiPermissions = [
            "Organization" => "None",
            "All Independent Activities" => "None",
            "Export/Import Activities" => "None",
            "All Projects" => "None",
            "Import/Export Projects" => "None",
            "Activity Types" => "None",
            "Activity Items" => "None",
            "Activity Layouts" => "None",
            "Subjects" => "None",
            "Education Levels" => "None",
            "Author Tags" => "None",
            "Manage Users" => "None",
            "Manage Roles" => "None",
            "LMS Settings" => "None",
            "LTI Tools" => "None",
            "BrightCove" => "None",
            "Media" => "None",
            "Google Classroom" => "None",
            "Microsoft Teams" => "None",
            "Project" => "View",
            "Playlist" => "View",
            "Activity" => "View",
            "Team" => "View",
            "Independent Activity" => "View",
            "My Interactive Video" => "None"
        ];

        $selfRegisteredUiPermissions = [
            "Organization" => "None",
            "All Independent Activities" => "None",
            "Export/Import Activities" => "None",
            "All Projects" => "None",
            "Import/Export Projects" => "None",
            "Activity Types" => "None",
            "Activity Items" => "None",
            "Activity Layouts" => "None",
            "Subjects" => "None",
            "Education Levels" => "None",
            "Author Tags" => "None",
            "Manage Users" => "None",
            "Manage Roles" => "None",
            "LMS Settings" => "None",
            "LTI Tools" => "None",
            "BrightCove" => "None",
            "Media" => "None",
            "Google Classroom" => "None",
            "Microsoft Teams" => "None",
            "Project" => "Edit",
            "Playlist" => "Edit",
            "Activity" => "Edit",
            "Team" => "Edit",
            "Independent Activity" => "Edit",
            "My Interactive Video" => "None"
        ];

        $roleTypes = OrganizationRoleType::whereIn('name', ['admin', 'course_creator', 'member', 'self_registered'])->get();
        $this->uiModules = DB::table('ui_modules')->whereNotNull('parent_id')->pluck('id', 'title');

        $uiModulePermissionsList = [];
        $uiModulePermissionsResult = DB::table('ui_module_permissions')->get();

        foreach ($uiModulePermissionsResult as $uiModulePermission) {
            $uiModulePermissionsList[$uiModulePermission->title][$uiModulePermission->ui_module_id] = $uiModulePermission->id;
        }

        $this->uiModulePermissions = $uiModulePermissionsList;

        $this->domain = request()->getHttpHost();

        return DB::transaction(function () use($roleTypes, $adminUiPermissions, $courseCreatorUiPermissions, $memberUiPermissions, $selfRegisteredUiPermissions) {
            foreach ($roleTypes as $roleType) {
                if ($roleType->name === 'admin') {
                    $this->assignPermissions($adminUiPermissions, $roleType);
                } else if ($roleType->name === 'course_creator') {
                    $this->assignPermissions($courseCreatorUiPermissions, $roleType);
                } else if ($roleType->name === 'member') {
                    $this->assignPermissions($memberUiPermissions, $roleType);
                } else if ($roleType->name === 'self_registered') {
                    $this->assignPermissions($selfRegisteredUiPermissions, $roleType);
                }
            }
        });
    }

    function assignPermissions($uiPermissions, $roleType)
    {
        $uiModulePermissionIds = [];

        foreach ($uiPermissions as $permissionName => $permissionType) {

            if (
                $this->domain === 'currikistudio.org' &&
                $permissionName === 'Team' &&
                ($roleType->name === 'course_creator' || $roleType->name === 'self_registered')
            ) {
                $permissionType = 'View';
            } else if ($this->domain === 'oci.currikistudio.org') {
                if ($permissionName === 'Independent Activity') {
                    $permissionType = 'None';
                } else if ($permissionName === 'My Interactive Video') {
                    $permissionType = 'View';
                }
            }

            if (isset($this->uiModules[$permissionName])) {
                $uiPermissionName = $this->uiModules[$permissionName];
                if (isset($this->uiModulePermissions[$permissionType][$uiPermissionName])) {
                    $uiModulePermissionIds[] = $this->uiModulePermissions[$permissionType][$uiPermissionName];
                }
            }
        }

        // assign ui role permissions
        $roleType->uiModulePermissions()->sync($uiModulePermissionIds);
        // assign backend role permissions
        $UiOrganizationPermissionMappingRepository = resolve(UiOrganizationPermissionMappingRepositoryInterface::class);
        $organizationPermissionTypeIds = $UiOrganizationPermissionMappingRepository->getOrganizationPermissionTypeIds($uiModulePermissionIds);
        $roleType->permissions()->sync($organizationPermissionTypeIds);
    }
}
