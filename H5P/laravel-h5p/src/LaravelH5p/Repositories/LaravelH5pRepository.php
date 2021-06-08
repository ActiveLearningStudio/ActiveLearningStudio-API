<?php

/*
 *
 * @Project        Laravel framework integration class
 * @Copyright      Djoudi
 * @Created        2018-2-20
 * @Filename       LaravelH5pRepository.php
 * @Description
 *
 */

namespace Djoudi\LaravelH5p\Repositories;

use Carbon\Carbon;
use Djoudi\LaravelH5p\Eloquents\H5pContent;
use Djoudi\LaravelH5p\Eloquents\H5pContentsLibrary;
use Djoudi\LaravelH5p\Eloquents\H5pContentsUserData;
use Djoudi\LaravelH5p\Eloquents\H5pLibrariesLibrary;
use Djoudi\LaravelH5p\Eloquents\H5pLibrary;
use Djoudi\LaravelH5p\Eloquents\H5pResult;
use Djoudi\LaravelH5p\Events\H5pEvent;
use Djoudi\LaravelH5p\Helpers\H5pHelper;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use H5PFrameworkInterface;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Psr\Http\Message\StreamInterface;

class LaravelH5pRepository implements H5PFrameworkInterface
{
    public $_download_file = '';

    /**
     * Keeps track of messages for the user.
     *
     * @since 1.0.0
     * @var array
     */
    protected $messages = array('error' => array(), 'updated' => array());

    /**
     * Implements setErrorMessage
     *
     * @param string $message
     * @param $code
     */
    public function setErrorMessage($message, $code = NULL)
    {
        if (H5pHelper::current_user_can('edit_h5p_contents')) {
            $this->messages['error'][] = $message;
        }
    }

    /**
     * Implements setInfoMessage
     *
     * @param string $message
     */
    public function setInfoMessage($message)
    {
        if (H5pHelper::current_user_can('edit_h5p_contents')) {
            $this->messages['updated'][] = $message;
        }
    }

    /**
     * Return the selected messages.
     *
     * @param string $type
     * @return array
     * @since 1.0.0
     */
    public function getMessages($type)
    {
        return isset($this->messages[$type]) ? $this->messages[$type] : NULL;
    }

    /**
     * Implements t
     *
     * @param string $message
     * @param array $replacements
     * @return string
     */
    public function t($message, $replacements = array())
    {
        // Insert !var as is, escape @var and emphasis %var.
        foreach ($replacements as $key => $replacement) {
            if ($key[0] === '@') {
                // $replacements[$key] = esc_html($replacement);
                $replacements[$key] = $replacement;
            } elseif ($key[0] === '%') {
                // $replacements[$key] = '<em>' . esc_html($replacement) . '</em>';
                $replacements[$key] = '<em>' . $replacement . '</em>';
            }
        }
        $message = preg_replace('/(!|@|%)[a-z0-9]+/i', '%s', $message);

        // Assumes that replacement vars are in the correct order.
        return vsprintf(trans($message), $replacements);
    }

    /**
     * Helper
     */
    private function getH5pPath()
    {
        // return url('vendor/h5p/h5p-core/');
        $plugin = App::make('LaravelH5p');
        return $plugin::get_h5plibrary_url('', TRUE);
    }

    /**
     * Get the URL to a library file
     *
     * @param string $libraryFolderName
     * @param string $fileName
     * @return string
     */
    public function getLibraryFileUrl($libraryFolderName, $fileName)
    {
        // return url('vendor/h5p/h5p-core/' . $libraryFolderName . '/' . $fileName);
        $plugin = App::make('LaravelH5p');
        return $plugin::get_h5plibrary_url($libraryFolderName . '/' . $fileName, TRUE);
    }

    /**
     * Implements getUploadedH5PFolderPath
     *
     * @return string
     */
    public function getUploadedH5pFolderPath()
    {
        static $dir;
        if (is_null($dir)) {
            $plugin = App::make('LaravelH5p');
            $dir = $plugin::$core->fs->getTmpPath();
        }
        return $dir;
    }

    /**
     * Implements getUploadedH5PPath
     *
     * @return string
     */
    public function getUploadedH5pPath()
    {
        static $path;
        if (is_null($path)) {
            $plugin = App::make('LaravelH5p');
            $path = $plugin::$core->fs->getTmpPath() . '.h5p';
        }
        return $path;
    }

    /**
     * Implements getLibraryId
     *
     * @param string $name
     * @param int $majorVersion
     * @param int $minorVersion
     * @return bool|int
     */
    public function getLibraryId($name, $majorVersion = NULL, $minorVersion = NULL)
    {
        $query = H5pLibrary::select()->where('name', $name);

        if ($majorVersion !== NULL) {
            $query->where('major_version', $majorVersion);
            if ($minorVersion !== NULL) {
                $query->where('minor_version', $minorVersion);
            }
        }

        $return = $query->orderBy('major_version', 'DESC')
            ->orderBy('minor_version', 'DESC')
            ->orderBy('patch_version', 'DESC')
            ->first();

        return $return === NULL ? FALSE : $return->id;
    }

    /**
     * Implements isPatchedLibrary
     *
     * @param $library
     * @return bool
     */
    public function isPatchedLibrary($library)
    {
        if (defined('H5P_DEV') && H5P_DEV) {
            return TRUE;
        }

        $operator = $this->isInDevMode() ? '<=' : '<';

        $return = DB::table('h5p_libraries')
            ->where('name', $library['machineName'])
            ->where('major_version', $library['majorVersion'])
            ->where('minor_version', $library['minorVersion'])
            ->where('patch_version', $operator, $library['patchVersion'])
            ->first();

        return $return !== NULL;
    }

    /**
     * Implements isInDevMode
     */
    public function isInDevMode()
    {
        return config('laravel-h5p.H5P_DEV');
    }

    /**
     * Implements mayUpdateLibraries
     */
    public function mayUpdateLibraries()
    {
        return H5pHelper::current_user_can('manage_h5p_libraries');
    }

    /**
     * Implements getLibraryUsage
     *
     * @param int $id
     * @param bool $skipContent
     * @return array
     */
    public function getLibraryUsage($id, $skipContent = FALSE)
    {
        if ($skipContent) {
            $content = -1;
        } else {
            $result = DB::select("SELECT COUNT(distinct c.id) AS cnt FROM h5p_libraries l JOIN h5p_contents_libraries cl ON l.id = cl.library_id JOIN h5p_contents c ON cl.content_id = c.id WHERE l.id = ?", [$id]);
            $content = intval($result[0]->cnt);
        }

        return array(
            'content' => $content,
            'libraries' => intval(H5pLibrariesLibrary::where('required_library_id', $id)->count()),
        );
    }

    /**
     * Implements saveLibraryData
     *
     * @param $library
     * @param bool $new
     */
    public function saveLibraryData(&$library, $new = TRUE)
    {
        $preloadedJs = $this->pathsToCsv($library, 'preloadedJs');
        $preloadedCss = $this->pathsToCsv($library, 'preloadedCss');

        $dropLibraryCss = '';
        if (isset($library['dropLibraryCss'])) {
            $libs = array();
            foreach ($library['dropLibraryCss'] as $lib) {
                $libs[] = $lib['machineName'];
            }
            $dropLibraryCss = implode(', ', $libs);
        }

        $embedTypes = '';
        if (isset($library['embedTypes'])) {
            $embedTypes = implode(', ', $library['embedTypes']);
        }

        if (!isset($library['semantics'])) {
            $library['semantics'] = '';
        }
        if (!isset($library['fullscreen'])) {
            $library['fullscreen'] = 0;
        }
        if (!isset($library['tutorial_url'])) {
            $library['tutorial_url'] = '';
        }
        if (!isset($library['hasIcon'])) {
            $library['hasIcon'] = 0;
        }

        if ($new) {
            $library['libraryId'] = DB::table('h5p_libraries')->insertGetId(array(
                'name' => $library['machineName'],
                'title' => $library['title'],
                'major_version' => $library['majorVersion'],
                'minor_version' => $library['minorVersion'],
                'patch_version' => $library['patchVersion'],
                'runnable' => $library['runnable'],
                'fullscreen' => $library['fullscreen'],
                'embed_types' => $embedTypes,
                'preloaded_js' => $preloadedJs,
                'preloaded_css' => $preloadedCss,
                'drop_library_css' => $dropLibraryCss,
                'semantics' => $library['semantics'],
                'tutorial_url' => $library['tutorial_url'],
                'has_icon' => $library['hasIcon'] ? 1 : 0,
            ));
        } else {
            $library['libraryId'] = DB::table('h5p_libraries')
                ->where('id', $library['libraryId'])->update(array(
                    'title' => $library['title'],
                    'patch_version' => $library['patchVersion'],
                    'runnable' => $library['runnable'],
                    'fullscreen' => $library['fullscreen'],
                    'embed_types' => $embedTypes,
                    'preloaded_js' => $preloadedJs,
                    'preloaded_css' => $preloadedCss,
                    'drop_library_css' => $dropLibraryCss,
                    'semantics' => $library['semantics'],
                    'has_icon' => $library['hasIcon'] ? 1 : 0,
                ));
            $this->deleteLibraryDependencies($library['libraryId']);
        }

        // Log library successfully installed/upgraded
        event(new H5pEvent('library', ($new ? 'create' : 'update'), NULL, NULL, $library['machineName'], $library['majorVersion'] . '.' . $library['minorVersion']));

        // Update languages
        DB::table('h5p_libraries_languages')
            ->where('library_id', $library['libraryId'])
            ->delete();

        if (isset($library['language'])) {
            foreach ($library['language'] as $languageCode => $translation) {
                DB::table('h5p_libraries_languages')->insert(array(
                    'library_id' => $library['libraryId'],
                    'language_code' => $languageCode,
                    'translation' => $translation,
                ));
            }
        }
    }

    /**
     * Convert list of file paths to csv
     *
     * @param array $library
     *  Library data as found in library.json files
     * @param string $key
     *  Key that should be found in $libraryData
     * @return string
     *  file paths separated by ', '
     */
    private function pathsToCsv($library, $key)
    {
        if (isset($library[$key])) {
            $paths = array();
            foreach ($library[$key] as $file) {
                $paths[] = $file['path'];
            }
            return implode(', ', $paths);
        }
        return '';
    }

    /**
     * Implements deleteLibraryDependencies
     *
     * @param $id
     */
    public function deleteLibraryDependencies($id)
    {
        DB::table('h5p_libraries_libraries')->where('library_id', $id)->delete();
    }

    /**
     * Implements deleteLibrary
     *
     * @param $library
     */
    public function deleteLibrary($library)
    {
        $plugin = App::make('LaravelH5p');

        // Delete library files
        $plugin::$core->deleteFileTree($this->getH5pPath() . '/libraries/' . $library->name . '-' . $library->major_version . '.' . $library->minor_version);

        // Remove library data from database
        DB::table('h5p_libraries_libraries')->where('library_id', $library->id)->delete();
        DB::table('h5p_libraries_languages')->where('library_id', $library->id)->delete();
        DB::table('h5p_libraries')->where('id', $library->id)->delete();
    }

    /**
     * Implements saveLibraryDependencies
     *
     * @param int $id
     * @param array $dependencies
     * @param string $dependencyType
     */
    public function saveLibraryDependencies($id, $dependencies, $dependencyType)
    {
        foreach ($dependencies as $dependency) {
            DB::insert("
                INSERT INTO h5p_libraries_libraries (library_id, required_library_id, dependency_type)
                SELECT ?, hl.id, ?
                FROM h5p_libraries hl
                WHERE name = ? AND major_version = ? AND minor_version = ?
                ON CONFLICT(library_id, required_library_id)
                DO UPDATE SET dependency_type = ?
            ", [$id, $dependencyType, $dependency['machineName'], $dependency['majorVersion'], $dependency['minorVersion'], $dependencyType]);
        }

        // DB::table('h5p_libraries_libraries')->insert($data);
    }

    /**
     * Implements updateContent
     *
     * @param $content
     * @param $contentMainId
     * @return mixed
     */
    public function updateContent($content, $contentMainId = NULL)
    {
        $user = Auth::user();        
        $current_date = date('Y-m-d H:i:s');
        $metadata = (array)$content['metadata'];
        $table = 'h5p_contents';

        $format = array();
        $data = array_merge(\H5PMetadata::toDBArray($metadata, true, true, $format), array(
            'updated_at' => $current_date,
            'parameters' => $content['params'],
            'embed_type' => 'div', // TODO: Determine from library?
            'library_id' => $content['library']['libraryId'],
            'filtered' => '',
            'disable' => $content['disable']
        ));

        if (!isset($content['id'])) {
            // Insert new content
            $data['created_at'] = $current_date;;
            $format[] = '%s';
            $data['user_id'] = 1; //$user->id;
            $format[] = '%d';
            $data['slug'] = Str::slug($metadata['title'], '-');
            $content_model = H5pContent::create($data);
            $content['id'] = $content_model->id;
            $event_type = 'create';
        } else {
            // Update existing content
            DB::table($table)->where('id', $content['id'])->update($data);
            $event_type = 'update';
        }

        // Log content create/update/upload
        if (!empty($content['uploaded'])) {
            $event_type .= ' upload';
        }

        event(new H5pEvent('content', $event_type, $content['id'], $metadata['title'], $content['library']['machineName'], $content['library']['majorVersion'] . '.' . $content['library']['minorVersion']));
        return $content['id'];
    }

    /**
     * Implements insertContent
     *
     * @param $content
     * @param $contentMainId
     * @return mixed
     */
    public function insertContent($content, $contentMainId = NULL)
    {
        return $this->updateContent($content);
    }

    /**
     * Implement getWhitelist
     *
     * @param bool $isLibrary
     * @param string $defaultContentWhitelist
     * @param string $defaultLibraryWhitelist
     * @return string
     */
    public function getWhitelist($isLibrary, $defaultContentWhitelist, $defaultLibraryWhitelist)
    {
        // TODO: Get this value from a settings page.
        $whitelist = $defaultContentWhitelist;
        if ($isLibrary) {
            $whitelist .= ' ' . $defaultLibraryWhitelist;
        }
        
        $whitelistArr = explode(' ', $whitelist);
        $whitelistCapitalized = array_map(function($ext) { return strtoupper($ext); }, $whitelistArr);
        $whitelistFinalArr = array_merge($whitelistArr, $whitelistCapitalized, ['html','htm','HTML','HTM']);
        $whitelist = implode(' ', $whitelistFinalArr);
        return $whitelist;
    }

    /**
     * Implements copyLibraryUsage
     *
     * @param $contentId
     * @param int $copyFromId
     * @param null $contentMainId
     */
    public function copyLibraryUsage($contentId, $copyFromId, $contentMainId = NULL)
    {
        DB::insert("
            INSERT INTO h5p_contents_libraries (content_id, library_id, dependency_type, weight, drop_css)
            SELECT ?, hcl.library_id, hcl.dependency_type, hcl.weight, hcl.drop_css
            FROM h5p_contents_libraries hcl
            WHERE hcl.content_id = ?
        ", [$contentId, $copyFromId]);
    }

    /**
     * Implements deleteContentData
     *
     * @param int $id
     */
    public function deleteContentData($id)
    {
        H5pContent::where('id', $id)->delete();
        $this->deleteLibraryUsage($id);
        H5pResult::where('content_id', $id)->delete();
        H5pContentsUserData::where('content_id', $id)->delete();
    }

    /**
     * Implements deleteLibraryUsage
     *
     * @param int $contentId
     */
    public function deleteLibraryUsage($contentId)
    {
        H5pContentsLibrary::where('content_id', $contentId)->delete();
    }

    /**
     * Implements saveLibraryUsage
     *
     * @param int $contentId
     * @param array $librariesInUse
     */
    public function saveLibraryUsage($contentId, $librariesInUse)
    {
        $dropLibraryCssList = array();
        foreach ($librariesInUse as $dependency) {
            if (!empty($dependency['library']['dropLibraryCss'])) {
                $dropLibraryCssList = array_merge($dropLibraryCssList, explode(', ', $dependency['library']['dropLibraryCss']));
            }
        }

        foreach ($librariesInUse as $dependency) {
            $dropCss = in_array($dependency['library']['machineName'], $dropLibraryCssList) ? 1 : 0;
            DB::table('h5p_contents_libraries')->insert(array(
                'content_id' => $contentId,
                'library_id' => $dependency['library']['libraryId'],
                'dependency_type' => $dependency['type'],
                'drop_css' => $dropCss,
                'weight' => $dependency['weight'],
            ));
        }
    }

    /**
     * Implements loadLibrary
     *
     * @param string $name
     * @param int $majorVersion
     * @param int $minorVersion
     * @return mixed
     */
    public function loadLibrary($name, $majorVersion, $minorVersion)
    {
        $library = DB::table('h5p_libraries')
            ->select([
                'id as libraryId',
                'name as machineName',
                'title',
                'major_version as majorVersion',
                'minor_version as minorVersion',
                'patch_version as patchVersion',
                'embed_types as embedTypes',
                'preloaded_js as preloadedJs',
                'preloaded_css as preloadedCss',
                'drop_library_css as dropLibraryCss',
                'fullscreen',
                'runnable',
                'semantics',
                'has_icon as hasIcon'
            ])
            ->where('name', $name)
            ->where('major_version', $majorVersion)
            ->where('minor_version', $minorVersion)
            ->first();

        $return = json_decode(json_encode($library), true);

        $dependencies = DB::select('
            SELECT hl.name as "machineName", hl.major_version as "majorVersion", hl.minor_version as "minorVersion", hll.dependency_type as "dependencyType"
            FROM h5p_libraries_libraries hll
            JOIN h5p_libraries hl ON hll.required_library_id = hl.id
            WHERE hll.library_id = ?
        ', [$library->libraryId]);

        foreach ($dependencies as $dependency) {
            $return[$dependency->dependencyType . 'Dependencies'][] = array(
                'machineName' => $dependency->machineName,
                'majorVersion' => $dependency->majorVersion,
                'minorVersion' => $dependency->minorVersion,
            );
        }

        if ($this->isInDevMode()) {
            $semantics = $this->getSemanticsFromFile($return['machineName'], $return['majorVersion'], $return['minorVersion']);
            if ($semantics) {
                $return['semantics'] = $semantics;
            }
        }

        return $return;
    }

    private function getSemanticsFromFile($name, $majorVersion, $minorVersion)
    {
        $semanticsPath = $this->getH5pPath() . '/libraries/' . $name . '-' . $majorVersion . '.' . $minorVersion . '/semantics.json';
        if (file_exists($semanticsPath)) {
            $semantics = file_get_contents($semanticsPath);
            if (!json_decode($semantics, TRUE)) {
                $this->setErrorMessage($this->t('Invalid json in semantics for %library', array('%library' => $name)));
            }
            return $semantics;
        }
        return FALSE;
    }

    /**
     * Implements loadLibrarySemantics
     *
     * @param string $name
     * @param int $majorVersion
     * @param int $minorVersion
     * @return mixed
     */
    public function loadLibrarySemantics($name, $majorVersion, $minorVersion)
    {
        if ($this->isInDevMode()) {
            $semantics = $this->getSemanticsFromFile($name, $majorVersion, $minorVersion);
        } else {
            $semantics = H5pLibrary::where('name', $name)
                ->where('major_version', $majorVersion)
                ->where('minor_version', $minorVersion)
                ->first();
            // DB::select("SELECT semantics FROM h5p_libraries WHERE name = ? AND major_version = ? AND minor_version = ?", [$name, $majorVersion, $minorVersion]);
        }

        return ($semantics === FALSE ? NULL : $semantics->semantics);
    }

    /**
     * Implements alterLibrarySemantics
     *
     * @param $semantics
     * @param string $name
     * @param int $majorVersion
     * @param int $minorVersion
     */
    public function alterLibrarySemantics(&$semantics, $name, $majorVersion, $minorVersion)
    {
        /**
         * Allows you to alter the H5P library semantics, i.e. changing how the
         * editor looks and how content parameters are filtered.
         *
         * @param object &$semantics
         * @param string $libraryName
         * @param int $libraryMajorVersion
         * @param int $libraryMinorVersion
         * @since 1.5.3
         *
         */
        // $this->alterLibrarySemantics($semantics, $name, $majorVersion, $minorVersion);
        // do_action_ref_array('h5p_alter_library_semantics', array(&$semantics, $name, $majorVersion, $minorVersion));
    }

    /**
     * Implements loadContent
     *
     * @param int $id
     * @return array
     */
    public function loadContent($id)
    {
        $content = DB::select('
            SELECT hc.id
                , hc.title
                , hc.parameters AS params
                , hc.filtered
                , hc.slug AS slug
                , hc.user_id
                , hc.embed_type AS "embedType"
                , hc.disable
                , hl.id AS "libraryId"
                , hl.name AS "libraryName"
                , hl.major_version AS "libraryMajorVersion"
                , hl.minor_version AS "libraryMinorVersion"
                , hl.embed_types AS "libraryEmbedTypes"
                , hl.fullscreen AS "libraryFullscreen"
                , hc.authors AS "authors"
                , hc.source AS "source"
                , hc.year_from AS "yearFrom"
                , hc.year_to AS "yearTo"
                , hc.license AS "license"
                , hc.license_version AS "licenseVersion"
                , hc.license_extras AS "licenseExtras"
                , hc.author_comments AS "authorComments"
                , hc.changes AS "changes"
                , hc.default_language AS "defaultLanguage"
            FROM h5p_contents hc
            JOIN h5p_libraries hl ON hl.id = hc.library_id
            WHERE hc.id = ?
        ', [$id]);

        if (count($content) > 0) {
            $content = (array) $content[0];
        } else {
            $content = null;
        }

        if ($content !== NULL) {
            $content['metadata'] = array();
            $metadata_structure = array('title', 'authors', 'source', 'yearFrom', 'yearTo', 'license', 'licenseVersion', 'licenseExtras', 'authorComments', 'changes', 'defaultLanguage');
            foreach ($metadata_structure as $property) {
                if (!empty($content[$property])) {
                    if ($property === 'authors' || $property === 'changes') {
                        $content['metadata'][$property] = json_decode($content[$property]);
                    } else {
                        $content['metadata'][$property] = $content[$property];
                    }
                    if ($property !== 'title') {
                        unset($content[$property]); // Unset all except title
                    }
                }
            }
        }

        H5pHelper::rearrangeContentParams($content, 'H5P.InteractiveBook');
        return $content;
    }

    /**
     * Implements loadContentDependencies
     *
     * @param int $id
     * @param $type
     * @return array
     */
    public function loadContentDependencies($id, $type = NULL)
    {
        $query = '
            SELECT hl.id
                , hl.name AS "machineName"
                , hl.major_version AS "majorVersion"
                , hl.minor_version AS "minorVersion"
                , hl.patch_version AS "patchVersion"
                , hl.preloaded_css AS "preloadedCss"
                , hl.preloaded_js AS "preloadedJs"
                , hcl.drop_css AS "dropCss"
                , hcl.dependency_type AS "dependencyType"
            FROM h5p_contents_libraries hcl
            JOIN h5p_libraries hl ON hcl.library_id = hl.id
            WHERE hcl.content_id = ?
        ';

        $queryArgs = array($id);
        if ($type !== NULL) {
            $query .= ' AND hcl.dependency_type = ?';
            $queryArgs[] = $type;
        }
        $query .= ' ORDER BY hcl.weight';

        $entries = DB::select($query, $queryArgs);
        $return = [];
        foreach ($entries as $entry) {
            $return[] = (array)$entry;
        }
        return $return;
    }

    /**
     * Implements getOption().
     *
     * @param string $name
     * @param bool $default
     * @return mixed
     */
    public function getOption($name, $default = FALSE)
    {
        if ($name === 'site_uuid') {
            $name = 'h5p_site_uuid'; // Make up for old core bug
            $name = 'site_key.h5p_' . $name;
        }
        return config('laravel-h5p.h5p_' . $name, $default);
    }

    /**
     * Implements setOption().
     *
     * @param string $name
     * @param $value
     */
    public function setOption($name, $value)
    {
        if ($name === 'site_uuid') {
            $name = 'h5p_site_uuid'; // Make up for old core bug
        }
        config(['laravel-h5p.h5p_' . $name => $value]);
    }

    /**
     * Convert variables to fit our DB.
     *
     * @param string $input
     * @return string
     */
    private static function camelToString($input)
    {
        $input = preg_replace('/[a-z0-9]([A-Z])[a-z0-9]/', '_$1', $input);
        return strtolower($input);
    }

    /**
     * Implements setFilteredParameters().
     *
     * @param int $id
     * @param array $fields
     */
    public function updateContentFields($id, $fields)
    {
        $processedFields = array();
        foreach ($fields as $name => $value) {
            $processedFields[self::camelToString($name)] = $value;
        }
        DB::table('h5p_contents')->where('id', $id)->update($processedFields);
    }

    /**
     * Implements clearFilteredParameters().
     *
     * @param int $library_id
     */
    public function clearFilteredParameters($library_id)
    {
        H5pContent::where('library_id', $library_id)->update(array('filtered' => NULL));
    }

    /**
     * Implements getNumNotFiltered().
     */
    public function getNumNotFiltered()
    {
        return H5pContent::where('filtered', '')->count();
    }

    /**
     * Implements getNumContent().
     *
     * @param int $library_id
     * @param $skip
     * @return int
     */
    public function getNumContent($library_id, $skip = NULL)
    {
        return H5pContent::where('library_id', $library_id)->count();
    }

    /**
     * Library list to load from library menu
     * Implements loadLibraries.
     *
     * @return array
     */
    public function loadLibraries()
    {
        $results = H5pLibrary::select([
            'id',
            'name',
            'title',
            'major_version',
            'minor_version',
            'patch_version',
            'runnable',
            'restricted'
        ])
            ->orderBy('title', 'ASC')
            ->orderBy('major_version', 'ASC')
            ->orderBy('minor_version', 'ASC')
            ->get();

        $libraries = array();
        foreach ($results as $library) {
            $libraries[$library->name][] = $library;
        }

        return $libraries;
    }

    /**
     * Implements getAdminUrl.
     */
    public function getAdminUrl()
    {
        return route('laravel-h5p.library');
    }

    /**
     * Implements getPlatformInfo
     *
     * @return array
     */
    public function getPlatformInfo()
    {
        $laravel = app();
        return array(
            'name' => 'laravel',
            'version' => $laravel::VERSION,
            'h5pVersion' => config('laravel-h5p.h5p_version'),
        );
    }

    /**
     * Implements fetchExternalData
     *
     * @param string $url
     * @param $data
     * @param bool $blocking
     * @param $stream
     * @return bool|StreamInterface|null
     * @throws GuzzleException
     */
    public function fetchExternalData($url, $data = NULL, $blocking = TRUE, $stream = NULL)
    {
        @set_time_limit(0);
        $options = array(
            'timeout' => !empty($blocking) ? 30 : 0.01,
            'stream' => !empty($stream),
            'filename' => !empty($stream) ? $stream : FALSE,
        );

        $client = new Client();
        try {
            if ($data !== NULL) {
                // Post
                $options['body'] = $data;
                $response = $client->request('POST', $url, array('form_params' => $options['body']));
            } else {
                // Get
                if (empty($options['filename'])) {
                    // Support redirects
                    // $response = wp_remote_get($url);
                    $response = $client->request('GET', $url);
                } else {
                    // Use safe when downloading files
                    // $response = wp_safe_remote_get($url, $options);
                    // $response = $client->request('GET', $url, $options);
                    $req_options = ['sink' => $options['filename']];
                    $response = $client->request('GET', $url, $req_options);
                }
            }

            if ($response->getStatusCode() === 200) {
                return empty($response->getBody()) ? TRUE : $response->getBody();
            } else {
                return NULL;
            }
        } catch (RequestException $e) {
            return FALSE;
        }
    }

    /**
     * Implements setLibraryTutorialUrl
     *
     * @param string $library_name
     * @param string $url
     */
    public function setLibraryTutorialUrl($library_name, $url)
    {
        DB::table('h5p_libraries')->where('name', $library_name)->update(array('tutorial_url' => $url));
    }

    /**
     * Implements resetContentUserData
     *
     * @param int $content_id
     */
    public function resetContentUserData($content_id)
    {
        // Reset user data for this content
        DB::table('h5p_contents_user_data')
            ->where('content_id', $content_id)
            ->where('invalidate', 1)
            ->update(array(
                'updated_at' => Carbon::now(),
                'data' => 'RESET',
            ));
    }

    /**
     * Implements isContentSlugAvailable
     *
     * @param $slug
     * @return array
     */
    public function isContentSlugAvailable($slug)
    {
        return DB::select("SELECT slug FROM h5p_contents WHERE slug = ':slug'", ['slub' => $slug]);
    }

    /**
     * Implements getLibraryContentCount
     */
    public function getLibraryContentCount()
    {
        $count = array();
        // Find number of content per library
        $results = DB::select("
            SELECT l.name, l.major_version, l.minor_version, COUNT(*) AS count
            FROM h5p_contents c, h5p_libraries l
            WHERE c.library_id = l.id
            GROUP BY l.name, l.major_version, l.minor_version
        ");
        // Extract results
        foreach ($results as $library) {
            $count[$library->name . ' ' . $library->major_version . '.' . $library->minor_version] = $library->count;
        }
        return $count;
    }

    /**
     * Implements getLibraryStats
     *
     * @param string $type
     * @return array
     */
    public function getLibraryStats($type)
    {
        $count = array();
        $results = DB::select("SELECT library_name AS name, library_version AS version, num FROM h5p_counters WHERE type = ?", [$type]);
        // Extract results
        foreach ($results as $library) {
            $count[$library->name . ' ' . $library->version] = $library->num;
        }
        return $count;
    }

    /**
     * Implements getNumAuthors
     */
    public function getNumAuthors()
    {
        return DB::select("SELECT COUNT(DISTINCT user_id) FROM h5p_contents");
    }

    // Magic stuff not used, we do not support library development mode.
    public function lockDependencyStorage()
    {

    }

    public function unlockDependencyStorage()
    {

    }

    /**
     * Implements saveCachedAssets
     *
     * @param string $key
     * @param array $libraries
     */
    public function saveCachedAssets($key, $libraries)
    {
        foreach ($libraries as $library) {
            // TODO: Avoid errors if they already exists...
            DB::table('h5p_libraries_cached_assets')->insert(array(
                'library_id' => isset($library['id']) ? $library['id'] : $library['libraryId'],
                'hash' => $key,
            ));
        }
    }

    /**
     * Implements deleteCachedAssets
     *
     * @param int $library_id
     * @return array
     */
    public function deleteCachedAssets($library_id)
    {
        // Get all the keys so we can remove the files
        $results = DB::select("SELECT hash FROM h5p_libraries_cached_assets WHERE library_id = ?", [$library_id]);
        // Remove all invalid keys
        $hashes = array();
        foreach ($results as $key) {
            $hashes[] = $key->hash;
            DB::table('h5p_libraries_cached_assets')->where('hash', $key->hash)->delete();
        }
        return $hashes;
    }

    /**
     * Implements afterExportCreated
     *
     * @param $content
     * @param string $filename
     */
    public function afterExportCreated($content, $filename)
    {
        $this->_download_file = storage_path('app/public/h5p/exports/' . $filename);
    }

    /**
     * Check if current user can edit H5P
     *
     * @method currentUserCanEdit
     * @param int $contentUserId
     * @return boolean
     */
    private static function currentUserCanEdit($contentUserId)
    {
        if (H5pHelper::current_user_can('edit_others_h5p_contents')) {
            return TRUE;
        }
        return get_current_user_id() == $contentUserId;
    }

    /**
     * Implements hasPermission
     *
     * @method hasPermission
     * @param H5PPermission $permission
     * @param $contentUserId
     * @return boolean
     */
    public function hasPermission($permission, $contentUserId = NULL)
    {
        switch ($permission) {
            case \H5PPermission::DOWNLOAD_H5P:
            case \H5PPermission::EMBED_H5P:
                // return self::currentUserCanEdit($contentUserId);
                return TRUE;
            case \H5PPermission::CREATE_RESTRICTED:
            case \H5PPermission::UPDATE_LIBRARIES:
                // return H5pHelper::current_user_can('manage_h5p_libraries');
                return TRUE;
            case \H5PPermission::INSTALL_RECOMMENDED:
                // H5pHelper::current_user_can('install_recommended_h5p_libraries');
                return TRUE;
        }
        return FALSE;
    }

    /**
     * Replaces existing content type cache with the one passed in
     *
     * @param object $contentTypeCache Json with an array called 'libraries'
     *  containing the new content type cache that should replace the old one.
     */
    public function replaceContentTypeCache($contentTypeCache)
    {
        // Replace existing content type cache
        DB::table('h5p_libraries_hub_cache')->truncate();

        foreach ($contentTypeCache->contentTypes as $ct) {
            // Insert into db
            DB::insert("
                INSERT INTO h5p_libraries_hub_cache (
                    machine_name,
                    major_version,
                    minor_version,
                    patch_version,
                    h5p_major_version,
                    h5p_minor_version,
                    title,
                    summary,
                    description,
                    icon,
                    created_at,
                    updated_at,
                    is_recommended,
                    popularity,
                    screenshots,
                    license,
                    example,
                    tutorial,
                    keywords,
                    categories,
                    owner
				) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
				",
                array(
                    $ct->id,
                    $ct->version->major,
                    $ct->version->minor,
                    $ct->version->patch,
                    $ct->coreApiVersionNeeded->major,
                    $ct->coreApiVersionNeeded->minor,
                    $ct->title,
                    $ct->summary,
                    $ct->description,
                    $ct->icon,
                    (new \DateTime($ct->createdAt))->getTimestamp(),
                    (new \DateTime($ct->updatedAt))->getTimestamp(),
                    $ct->isRecommended === TRUE ? 1 : 0,
                    $ct->popularity,
                    json_encode($ct->screenshots),
                    json_encode(isset($ct->license) ? $ct->license : array()),
                    $ct->example,
                    isset($ct->tutorial) ? $ct->tutorial : '',
                    json_encode(isset($ct->keywords) ? $ct->keywords : array()),
                    json_encode(isset($ct->categories) ? $ct->categories : array()),
                    $ct->owner
                )
            );
        }
    }

    public function libraryHasUpgrade($library)
    {
        return false;
    }

    public function loadAddons()
    {
        return [];
    }

    public function getLibraryConfig($libraries = NULL)
    {
        return [];
    }
}
