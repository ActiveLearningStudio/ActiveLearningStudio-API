<?php

/*
 *
 * @Project
 * @Copyright      Djoudi
 * @Created        2018-02-13
 * @Filename       EditorAjaxRepository.php
 * @Description
 *
 */

namespace Djoudi\LaravelH5p\Repositories;

use Djoudi\LaravelH5p\Eloquents\H5pLibrariesHubCache;
use H5PEditorAjaxInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EditorAjaxRepository implements H5PEditorAjaxInterface
{
    public function getAuthorsRecentlyUsedLibraries()
    {
        global $wpdb;
        $recently_used = array();
        $user = Auth::user();
        $user_id = $user->id;
        $result = DB::select("
            SELECT library_name, max(created_at) AS max_created_at
            FROM h5p_events
            WHERE type='content' AND sub_type = 'create' AND user_id = $user_id
			GROUP BY library_name
			ORDER BY max_created_at DESC
        ");

        foreach ($result as $row) {
            $recently_used[] = $row->library_name;
        }

        return $recently_used;
    }

    public function getContentTypeCache($machineName = NULL)
    {
        $libraries_hub = H5pLibrariesHubCache::select();
        if ($machineName) {
            return $libraries_hub->where('machine_name', $machineName)->pluck('id', 'is_recommended');
        } else {
            $collection = $libraries_hub->get();
            $rows = [];
            foreach ($collection as $key => $record) {
                $row = new \stdClass();
                $row->id = $record->id;
                $row->machine_name = $record->machine_name;
                $row->major_version = $record->major_version;
                $row->minor_version = $record->minor_version;
                $row->patch_version = $record->patch_version;
                $row->h5p_major_version = $record->h5p_major_version;
                $row->h5p_minor_version = $record->h5p_minor_version;
                $row->title = $record->title;
                $row->summary = $record->summary;
                $row->description = $record->description;
                $row->icon = $record->icon;
                $row->created_at = $record->created_at;
                $row->updated_at = $record->updated_at;
                $row->is_recommended = $record->is_recommended;
                $row->popularity = $record->popularity;
                $row->screenshots = $record->screenshots;
                $row->license = $record->license;
                $row->example = $record->example;
                $row->tutorial = $record->tutorial;
                $row->keywords = $record->keywords;
                $row->categories = $record->categories;
                $row->owner = $record->owner;
                $rows[] = $row;
            }

            return $rows;
        }
    }

    public function getLatestLibraryVersions()
    {
        // Get latest version of local libraries
        $major_versions_sql = "
            SELECT hl.name, MAX(hl.major_version) AS major_version
            FROM h5p_libraries hl
            WHERE hl.runnable = 1
			GROUP BY hl.name
        ";

        $minor_versions_sql ="
            SELECT hl2.name, hl2.major_version, MAX(hl2.minor_version) AS minor_version
            FROM ({$major_versions_sql}) hl1
            JOIN h5p_libraries hl2 ON hl1.name = hl2.name
            AND hl1.major_version = hl2.major_version
			GROUP BY hl2.name, hl2.major_version
        ";

        return DB::select("
            SELECT hl4.id,
                hl4.name AS machine_name,
                hl4.title,
                hl4.major_version,
                hl4.minor_version,
                hl4.patch_version,
                hl4.restricted,
                hl4.has_icon
            FROM ({$minor_versions_sql}) hl3
            JOIN h5p_libraries hl4 ON hl3.name = hl4.name
            AND hl3.major_version = hl4.major_version
            AND hl3.minor_version = hl4.minor_version
        ");
    }

    public function validateEditorToken($token)
    {
        // return (Helpers::nonce($token) == 'h5p_editor_ajax');
        return true;
    }

    public function getTranslations($libraries, $language_code)
    {
        return [];
    }
}
