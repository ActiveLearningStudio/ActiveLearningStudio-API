<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReorderUserOrgProjectsInDefaultOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = DB::table('users')
                    ->select('id')
                    ->whereNull('deleted_at')
                    ->get();

        foreach ($users as $user) {
            $userOrganizations = DB::table('organization_user_roles')
                                    ->select('organization_id')
                                    ->distinct('organization_id')
                                    ->where('user_id', '=', $user->id)
                                    ->whereNull('deleted_at')
                                    ->get();

            $userProjects = DB::table('user_project')
                                    ->where('user_id', '=', $user->id)
                                    ->whereNull('deleted_at')
                                    ->pluck('project_id');

            foreach ($userOrganizations as $userOrganization) {
                $userOrganizationProjects = DB::table('projects')
                                    ->where('organization_id', '=', $userOrganization->organization_id)
                                    ->whereIn('id', $userProjects)
                                    ->whereNull('deleted_at')
                                    ->whereNull('team_id')
                                    ->orderBy('order', 'asc')
                                    ->pluck('id');

                foreach ($userOrganizationProjects as $order => $id) {
                    $affected = DB::table('projects')
                                    ->where('id', $id)
                                    ->update(['order' => $order]);
                }
            }
        }
    }
}
