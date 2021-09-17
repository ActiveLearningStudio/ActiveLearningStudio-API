<?php

/*
 *
 * @Project        djoudi/laravel-h5p
 * @Copyright      Djoudi
 * @Created        2017-02-21
 * @Filename       LaravelH5p.php
 * @Description
 *
 */

namespace Djoudi\LaravelH5p;

use Djoudi\LaravelH5p\Repositories\EditorAjaxRepository;
use Djoudi\LaravelH5p\Repositories\LaravelH5pRepository;
use Djoudi\LaravelH5p\Storages\EditorStorage;
use Djoudi\LaravelH5p\Storages\LaravelH5pStorage;
use H5PContentValidator;
use H5PCore;

//use H5PDevelopment;
//use H5PDefaultStorage;
//use H5PEditorEndpoints;
//use H5PEditorAjax;
//use H5PEditorAjaxInterface;
//use H5peditorFile;
//use H5peditorStorage;
use H5peditor;
use H5PExport;
use H5PStorage;
use H5PValidator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LaravelH5p
{
    /**
     * Instance of H5P WordPress Framework Interface.
     *
     * @since 1.0.0
     */
    public static $core = null;
    public static $h5peditor = null;
    public static $interface = null;
    public static $validator = null;
    public static $storage = null;
    public static $contentvalidator = null;
    public static $export = null;
    public static $settings = null;

    public function __construct()
    {
        self::$interface = new LaravelH5pRepository();
        self::$core = new H5PCore(
            self::$interface,
            self::get_h5p_storage('', TRUE),
            self::get_h5p_url(),
            config('laravel-h5p.language'),
            config('laravel-h5p.h5p_export')
        );
        self::$core->aggregateAssets = config('laravel-h5p.H5P_DISABLE_AGGREGATION');
        self::$validator = new H5PValidator(self::$interface, self::$core);
        self::$storage = new H5PStorage(self::$interface, self::$core);
        self::$contentvalidator = new H5PContentValidator(self::$interface, self::$core);
        self::$export = new H5PExport(self::$interface, self::$core);
        self::$h5peditor = new H5peditor(self::$core, new EditorStorage(), new EditorAjaxRepository());
        // self::$h5peditor = new H5peditor(self::$core, new EditorStorage(), new EditorAjaxRepository());
    }

    /**
     * Parse version string into smaller components.
     *
     * @param string $version
     * @return object|boolean False on failure to parse
     * @since 1.7.9
     */
    public static function split_version($version)
    {
        $version_parts = explode('.', $version);
        if (count($version_parts) !== 3) {
            return FALSE;
        }

        return (object)array(
            'major' => (int)$version_parts[0],
            'minor' => (int)$version_parts[1],
            'patch' => (int)$version_parts[2],
        );
    }

    public static function get_url($path = '')
    {
        return config('app.url') . Storage::url($path);
    }

    public static function get_h5p_storage($path = '', $absolute = FALSE)
    {
        if ($absolute) {
            $h5p_path = storage_path('app/public/h5p' . $path);
            if (!file_exists($h5p_path)) {
                mkdir($h5p_path, 0777, true);
            }

            return new LaravelH5pStorage($h5p_path);
            // return storage_path('h5p' . $path);
        } else {
            return self::get_url('h5p' . $path);
        }
    }

    public static function get_laravelh5p_url($path = '')
    {
        return self::get_url('h5p/laravel-h5p' . $path);
    }

    public static function get_h5p_url($path = '')
    {
        return self::get_url('h5p' . $path);
    }

    public static function get_h5pcore_url($path = '')
    {
        return self::get_h5p_url('/h5p-core' . $path);
    }

    public static function get_h5peditor_url($path = '')
    {
        return self::get_h5p_url('/h5p-editor' . $path);
    }

    public static function get_h5plibrary_url($path = '', $absolute = FALSE)
    {
        $return = self::get_url('h5p' . $path);
        if ($absolute) {
            return Storage::url('h5p/libraries/' . $path);
        } else {
            return self::get_url('h5p' . $path);
        }
    }

    public static function get_service_url($path = '')
    {
        return route('h5p.index', [], false);
    }

    public static function get_core($settings = array())
    {
        $settings = self::get_core_settings($settings);
        $settings = self::get_core_files($settings);
        return $settings;
    }

    public static function get_editor($content = null)
    {
        $settings = self::get_editor_settings($content);
        $settings = self::get_editor_assets($settings, $content);
        return $settings;
    }

    /**
     * Include settings and assets for the given content.
     *
     * @param array $content
     * @param boolean $no_cache
     * @return array
     * @since 1.0.0
     */
    public function get_embed($content, $settings, $no_cache = FALSE)
    {
        // Determine embed type
        $embed = H5PCore::determineEmbedType($content['embedType'], $content['library']['embedTypes']);

        // Make sure content isn't added twice
        $cid = 'cid-' . $content['id'];

        if (!isset($settings['contents'][$cid])) {
            $settings['contents'][$cid] = self::get_content_settings($content);
            $core = self::$core;
            // Get assets for this content
            $preloaded_dependencies = $core->loadContentDependencies($content['id'], 'preloaded');
            $files = $core->getDependenciesFiles($preloaded_dependencies);
            self::alter_assets($files, $preloaded_dependencies, $embed);
            if ($embed === 'div') {
                foreach ($files['scripts'] as $script) {
                    $url = $script->path . $script->version;
                    if (!in_array($url, $settings['loadedJs'])) {
                        $settings['loadedJs'][] = self::get_h5plibrary_url($url);
                    }
                }

                foreach ($files['styles'] as $style) {
                    $url = $style->path . $style->version;
                    if (!in_array($url, $settings['loadedCss'])) {
                        $settings['loadedCss'][] = self::get_h5plibrary_url($url);
                    }
                }
            } elseif ($embed === 'iframe') {
                $settings['contents'][$cid]['scripts'] = $core->getAssetsUrls($files['scripts']);
                $settings['contents'][$cid]['styles'] = $core->getAssetsUrls($files['styles']);
            }
        }

        if ($embed === 'div') {
            return array(
                'settings' => $settings,
                'embed' => '<div class="h5p-content" data-content-id="' . $content['id'] . '"></div>',
            );
        } else {
            return array(
                'settings' => $settings,
                'embed' => '<div class="h5p-iframe-wrapper"><iframe id="h5p-iframe-' . $content['id'] . '" class="h5p-iframe" data-content-id="' . $content['id'] . '" style="height: 1px" src="about:blank" frameBorder="0" scrolling="no"></iframe></div>',
            );
        }
    }

    /**
     * The most basic settings
     *
     * @return array
     */
    private static function get_core_settings()
    {
        $contentUserDataUrl = config('app.url') . '/api/v1/h5p/ajax/content-user-data' . 
        '?content_id=:contentId&data_type=:dataType&sub_content_id=:subContentId';
        if (isset($_GET['gcuid']) && isset($_GET['submissionid'])) {
            $contentUserDataUrl = config('app.url') . '/api/v1/google-classroom/h5p/ajax/content-user-data' . 
            '?content_id=:contentId&data_type=:dataType&sub_content_id=:subContentId' . 
            '&gcuid=' . $_GET['gcuid'] . '&submissionid=' . $_GET['submissionid'];
        }

        $settings = array(
            'baseUrl' => config('app.url'),
            'url' => self::get_h5p_storage(), // for uploaded
            'postUserStatistics' => config('laravel-h5p.h5p_track_user') && Auth::check(),
            'ajax' => array(
                'setFinished' => config('app.url') . '/api/v1/h5p/ajax/finish', // route('h5p.ajax.finish')
                'contentUserData' => $contentUserDataUrl,
            ),
            'saveFreq' => config('laravel-h5p.h5p_save_content_state'),
            'siteUrl' => config('app.url'),
            'l10n' => array(
                'H5P' => trans('laravel-h5p.h5p'),
            ),
            'hubIsEnabled' => config('laravel-h5p.h5p_hub_is_enabled'),
            'reportingIsEnabled' => config('laravel-h5p.h5p_enable_lrs_content_types'),
        );

        if (Auth::check()) {
            $settings['user'] = array(
                'name' => Auth::user()->name,
                'mail' => Auth::user()->email,
            );
        } elseif (isset($_GET['gcuid'])) {
            $settings['user'] = array(
                'name' => $_GET['gcuid'],
                'mail' => $_GET['gcuid'] . '@currikistudio.org',
            );
        }

        return $settings;
    }

    private static function get_core_files($settings = array())
    {
        $settings['loadedJs'] = array();
        $settings['loadedCss'] = array();

        $settings['core'] = array(
            'styles' => array(),
            'scripts' => array(),
        );

        $settings['core']['styles'][] = self::get_laravelh5p_url('/css/laravel-h5p.css');

        foreach (H5PCore::$styles as $style) {
            $settings['core']['styles'][] = self::get_h5pcore_url('/' . $style);
        }
        foreach (H5PCore::$scripts as $script) {
            $settings['core']['scripts'][] = self::get_h5pcore_url('/' . $script);
        }

        $settings['core']['scripts'][] = self::get_h5peditor_url('/scripts/h5peditor-editor.js');
        $settings['core']['scripts'][] = self::get_laravelh5p_url('/js/laravel-h5p.js');
        $settings['core']['scripts'][] = self::get_h5plibrary_url('/libraries/H5P.JoubelUI-1.3/js/joubel-help-dialog.js?ver=1.3.9');
        $settings['core']['scripts'][] = self::get_h5plibrary_url('/libraries/H5P.JoubelUI-1.3/js/joubel-message-dialog.js?ver=1.3.9');
        $settings['core']['scripts'][] = self::get_h5plibrary_url('/libraries/H5P.JoubelUI-1.3/js/joubel-progress-circle.js?ver=1.3.9');
        $settings['core']['scripts'][] = self::get_h5plibrary_url('/libraries/H5P.JoubelUI-1.3/js/joubel-simple-rounded-button.js?ver=1.3.9');
        $settings['core']['scripts'][] = self::get_h5plibrary_url('/libraries/H5P.JoubelUI-1.3/js/joubel-speech-bubble.js?ver=1.3.9');
        $settings['core']['scripts'][] = self::get_h5plibrary_url('/libraries/H5P.JoubelUI-1.3/js/joubel-throbber.js?ver=1.3.9');
        $settings['core']['scripts'][] = self::get_h5plibrary_url('/libraries/H5P.JoubelUI-1.3/js/joubel-tip.js?ver=1.3.9');
        $settings['core']['scripts'][] = self::get_h5plibrary_url('/libraries/H5P.JoubelUI-1.3/js/joubel-slider.js?ver=1.3.9');
        $settings['core']['scripts'][] = self::get_h5plibrary_url('/libraries/H5P.JoubelUI-1.3/js/joubel-score-bar.js?ver=1.3.9');
        $settings['core']['scripts'][] = self::get_h5plibrary_url('/libraries/H5P.JoubelUI-1.3/js/joubel-progressbar.js?ver=1.3.9');
        $settings['core']['scripts'][] = self::get_h5plibrary_url('/libraries/H5P.JoubelUI-1.3/js/joubel-ui.js?ver=1.3.9');

        return $settings;
    }

    private static function get_editor_settings($content = null)
    {
        $settings = self::get_core_settings();

        $settings['editor'] = array(
            'filesPath' => self::get_h5p_storage('/editor'),
            'fileIcon' => array(
                'path' => self::get_h5peditor_url('/images/binary-file.png'),
                'width' => 50,
                'height' => 50,
            ),
            'ajaxPath' => config('app.url') . '/api/v1/h5p/ajax/',
            //'ajaxPath' => 'http://localhost:8282' . '/api/v1/h5p/ajax/',
            // for checkeditor,
            'libraryUrl' => self::get_h5peditor_url() . '/',
            'copyrightSemantics' => self::$contentvalidator->getCopyrightSemantics(),
            'metadataSemantics' => self::$contentvalidator->getMetadataSemantics(),
            'assets' => [],
            'deleteMessage' => trans('laravel-h5p.content.destoryed'),
            'apiVersion' => H5PCore::$coreApi
        );

        // contents
        if ($content !== NULL) {
            $settings['editor']['nodeVersionId'] = $content['id'];
        }

        return $settings;
    }

    private static function get_editor_assets($settings = array(), $content = null)
    {
        $settings = self::get_core_files($settings);

        // load core assets
        $settings['editor']['assets']['css'] = $settings['core']['styles'];
        $settings['editor']['assets']['js'] = $settings['core']['scripts'];
        $settings['editor']['assets']['js'][] = self::get_laravelh5p_url('/js/laravel-h5p-editor.js');

        // add editor styles
        foreach (H5peditor::$styles as $style) {
            $settings['editor']['assets']['css'][] = self::get_h5peditor_url('/' . $style);
        }

        // Add editor JavaScript
        foreach (H5peditor::$scripts as $script) {
            // We do not want the creator of the iframe inside the iframe
            if ($script !== 'scripts/h5peditor-editor.js') {
                $settings['editor']['assets']['js'][] = self::get_h5peditor_url('/' . $script);
            }
        }

        $language_script = '/language/' . self::get_language() . '.js';
        $settings['editor']['assets']['js'][] = self::get_h5peditor_url($language_script);

        if ($content) {
            $settings = self::get_content_files($settings, $content);
        }

        return $settings;
    }

    /**
     * @param $content
     * @return array
     */
    public static function get_content_settings($content)
    {
        $safe_parameters = self::$core->filterParameters($content);
//        if (has_action('h5p_alter_filtered_parameters')) {
//            // Parse the JSON parameters
//            $decoded_parameters = json_decode($safe_parameters);
//            /**
//             * Allows you to alter the H5P content parameters after they have been
//             * filtered. This hook only fires before view.
//             *
//             * @param object &$parameters
//             * @param string $libraryName
//             * @param int $libraryMajorVersion
//             * @param int $libraryMinorVersion
//             * @since 1.5.3
//             *
//             */
//            // Stringify the JSON parameters
//            $safe_parameters = json_encode($decoded_parameters);
//        }

        // Getting author's user id
        $author_id = (int)(is_array($content) ? $content['user_id'] : $content->user_id);

        // Add JavaScript settings for this content
        $settings = array(
            'library' => H5PCore::libraryToString($content['library']),
            'jsonContent' => $safe_parameters,
            'fullScreen' => $content['library']['fullscreen'],
            'exportUrl' => config('laravel-h5p.h5p_export') ? config('app.url') . '/api/v1/h5p/export/' . $content['id'] : '',
            'embedCode' => '<iframe src="' . config('laravel-h5p.FRONT_END_URL') . '/h5p/embed/' . $content['id'] . '" width=":w" height=":h" frameborder="0" allowfullscreen="allowfullscreen"></iframe>',
            'resizeCode' => '<script src="' . self::get_h5pcore_url('/js/h5p-resizer.js') . '" charset="UTF-8"></script>',
            'url' => route('h5p.embed', ['id' => $content['id']]),
            'title' => $content['title'],
            'displayOptions' => self::$core->getDisplayOptionsForView($content['disable'], $author_id),
            'contentUserData' => array(
                0 => array(
                    'state' => '{}',
                ),
            ),
            'metadata' => isset($content['metadata']) ? $content['metadata'] : ['title' => null]
        );

        // Get preloaded user data for the current user
        /*if (config('laravel-h5p.h5p_save_content_state') && Auth::check()) {
            $results = DB::select("
                SELECT hcud.sub_content_id, hcud.data_id, hcud.data
                FROM h5p_contents_user_data hcud
                WHERE user_id = ?
                AND content_id = ?
                AND preload = 1
            ", [Auth::user()->id, $content['id']]);

            if ($results) {
                foreach ($results as $result) {
                    $settings['contentUserData'][$result->sub_content_id][$result->data_id] = $result->data;
                }
            }
        }*/
        
        // Get preloaded user data for the current user
        if (isset($_GET['gcuid']) && isset($_GET['submissionid'])) {
            $results = DB::select("
                SELECT hcud.sub_content_id, hcud.data_id, hcud.data, hcud.submission_id
                FROM h5p_contents_user_data_go hcud
                WHERE user_id = ?
                AND content_id = ?
                AND submission_id = ?
                AND preload = true
            ", [$_GET['gcuid'], $content['id'], $_GET['submissionid']]);

            if ($results) {
                foreach ($results as $result) {
                    $settings['contentUserData'][$result->sub_content_id][$result->data_id] = $result->data;
                }
            }
        }

        return $settings;
    }

    /**
     * @param array $settings
     * @param $content
     * @return array
     */
    public static function get_content_files($settings, $content)
    {
        $embed = H5PCore::determineEmbedType($content['embedType'], $content['library']['embedTypes']);

        // Make sure content isn't added twice
        $cid = 'cid-' . $content['id'];
        if (!isset($settings['contents'][$cid])) {
            // Load File
            $settings['contents'][$cid] = self::get_content_settings($content);

            $core = self::$core;

            // Get assets for this content
            $preloaded_dependencies = $core->loadContentDependencies($content['id'], 'preloaded');

            $files = $core->getDependenciesFiles($preloaded_dependencies);

            self::alter_assets($files, $preloaded_dependencies, $embed);

            if ($embed === 'div') {
                // $this->enqueue_assets($files);
                foreach ($files['scripts'] as $script) {
                    $url = $script->path . $script->version;
                    if (!in_array($url, $settings['loadedJs'])) {
                        $settings['loadedJs'][] = self::get_h5plibrary_url($url);
                    }
                }

                foreach ($files['styles'] as $style) {
                    $url = $style->path . $style->version;
                    if (!in_array($url, $settings['loadedCss'])) {
                        $settings['loadedCss'][] = self::get_h5plibrary_url($url);
                    }
                }
            } elseif ($embed === 'iframe') {
                $settings['contents'][$cid]['scripts'] = $core->getAssetsUrls($files['scripts']);
                $settings['contents'][$cid]['styles'] = $core->getAssetsUrls($files['styles']);
            }
        }

        return $settings;
    }

    public static function alter_assets(&$files, &$dependencies, $embed)
    {
        $libraries = array();
        foreach ($dependencies as $dependency) {
            $libraries[$dependency['machineName']] = array(
                'majorVersion' => $dependency['majorVersion'],
                'minorVersion' => $dependency['minorVersion'],
            );
        }
        return $libraries;
    }

    /**
     * Get content with given id.
     *
     * @param int $id
     * @return array
     * @since 1.0.0
     */
    public static function get_content($id = null)
    {
        if ($id === FALSE || $id === NULL) {
            return trans('h5p.content.missing_h5p_identifier');
        }

        // Try to find content with $id.
        $content = self::$core->loadContent($id);

        if (!$content) {
            return trans('h5p.content.can_not_find_content', ['id' => $id]);
        }
        $content['language'] = self::get_language();

        return $content;
    }

    public function load_content($id)
    {
        $content = self::get_content($id);
        if (!is_string($content)) {
            $tags = DB::select(
                "SELECT t.name
                FROM h5p_contents_tags ct
                JOIN h5p_tags t ON ct.tag_id = t.id
                WHERE ct.content_id = ?",
                [$id]
            );
            $content['tags'] = '';
            foreach ($tags as $tag) {
                $content['tags'] .= ($content['tags'] !== '' ? ', ' : '') . $tag->name;
            }
        }

        return $content;
    }

    public static function get_language()
    {
        return config('laravel-h5p.language');
    }
}
