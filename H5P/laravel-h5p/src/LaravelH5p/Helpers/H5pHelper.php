<?php

/*
 *
 * @Project        Expression project.displayName is undefined on line 5, column 35 in Templates/Licenses/license-default.txt.
 * @Copyright      Djoudi
 * @Created        2017-02-20
 * @Filename       H5pHelper.php
 * @Description
 *
 */

namespace Djoudi\LaravelH5p\Helpers;

class H5pHelper
{
    public static function current_user_can($permission)
    {
        $currentUserCan = true;
        $permissionsConfig = ["manage_h5p_libraries" => false];
        if (array_key_exists($permission, $permissionsConfig)) {
            $currentUserCan = $permissionsConfig[$permission];
        }
        return $currentUserCan;
    }

    public static function nonce($token)
    {
        return bin2hex($token);
    }

    public function handle_new_content_save()
    {
        $contentExists = ($this->content !== NULL && !is_string($this->content));
        $hubIsEnabled = get_option('h5p_hub_is_enabled', TRUE);

        $plugin = H5P_Plugin::get_instance();
        $core = $plugin->get_h5p_instance('core');

        // Prepare form
        $library = $this->get_input('library', $contentExists ? H5PCore::libraryToString($this->content['library']) : 0);
        $parameters = $this->get_input('parameters', '{"params":' . ($contentExists ? $core->filterParameters($this->content) : '{}') . ',"metadata":' . ($contentExists ? json_encode((object)$this->content['metadata']) : '{}') . '}');

        // Determine upload or create
        if (!$hubIsEnabled && !$contentExists && !$this->has_libraries()) {
            $upload = TRUE;
            $examplesHint = TRUE;
        } else {
            $upload = (filter_input(INPUT_POST, 'action') === 'upload');
            $examplesHint = FALSE;
        }

        // Filter/escape parameters, double escape that is...
        $safe_text = wp_check_invalid_utf8($parameters);
        $safe_text = _wp_specialchars($safe_text, ENT_QUOTES, false, true);
        $parameters = apply_filters('attribute_escape', $safe_text, $parameters);
    }

    public function process_new_content($echo_on_success)
    {
        $plugin = H5P_Plugin::get_instance();

        $consent = filter_input(INPUT_POST, 'consent', FILTER_VALIDATE_BOOLEAN);
        if ($consent !== NULL && !get_option('h5p_has_request_user_consent', FALSE) && current_user_can('manage_options')) {
            check_admin_referer('h5p_consent', 'can_has'); // Verify form
            update_option('h5p_hub_is_enabled', $consent);
            update_option('h5p_send_usage_statistics', $consent);
            update_option('h5p_has_request_user_consent', TRUE);
        }

        // Check if we have any content or errors loading content
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        if ($id) {
            $this->load_content($id);
            if (is_string($this->content)) {
                H5P_Plugin_Admin::set_error($this->content);
                $this->content = NULL;
            }
        }

        if ($this->content !== NULL) {
            // We have existing content
            if (!$this->current_user_can_edit($this->content)) {
                // The user isn't allowed to edit this content
                H5P_Plugin_Admin::set_error(__('You are not allowed to edit this content.', $this->plugin_slug));
                return;
            }

            // Check if we're deleting content
            $delete = filter_input(INPUT_GET, 'delete');
            if ($delete) {
                if (wp_verify_nonce($delete, 'deleting_h5p_content')) {
                    $this->set_content_tags($this->content['id']);
                    $storage = $plugin->get_h5p_instance('storage');
                    $storage->deletePackage($this->content);

                    // Log content delete
                    new H5P_Event(
                        'content',
                        'delete',
                        $this->content['id'],
                        $this->content['title'],
                        $this->content['library']['name'],
                        $this->content['library']['majorVersion'] . '.' . $this->content['library']['minorVersion']
                    );

                    wp_safe_redirect(admin_url('admin.php?page=h5p'));
                    return;
                }

                H5P_Plugin_Admin::set_error(__('Invalid confirmation code, not deleting.', $this->plugin_slug));
            }
        }

        // Check if we're uploading or creating content
        $action = filter_input(
            INPUT_POST,
            'action',
            FILTER_VALIDATE_REGEXP,
            array('options' => array('regexp' => '/^(upload|create)$/'))
        );
        if ($action) {
            check_admin_referer('h5p_content', 'yes_sir_will_do'); // Verify form
            $core = $plugin->get_h5p_instance('core'); // Make sure core is loaded

            $result = FALSE;
            if ($action === 'create') {
                // Handle creation of new content.
                $result = $this->handle_content_creation($this->content);
            } elseif (isset($_FILES['h5p_file']) && $_FILES['h5p_file']['error'] === 0) {
                // Create new content if none exists
                $content = ($this->content === NULL ? array('disable' => H5PCore::DISABLE_NONE) : $this->content);
                $content['uploaded'] = true;
                $this->get_disabled_content_features($core, $content);

                // Handle file upload
                $plugin_admin = H5P_Plugin_Admin::get_instance();
                $result = $plugin_admin->handle_upload($content);
            }

            if ($result) {
                $content['id'] = $result;
                $this->set_content_tags($content['id'], filter_input(INPUT_POST, 'tags'));
                if (empty($echo_on_success)) {
                    wp_safe_redirect(admin_url('admin.php?page=h5p&task=show&id=' . $result));
                } else {
                    echo $echo_on_success;
                }
                exit;
            }
        }
    }

    private function handle_content_creation($content)
    {
        $plugin = H5P_Plugin::get_instance();
        $core = $plugin->get_h5p_instance('core');

        // Keep track of the old library and params
        $oldLibrary = NULL;
        $oldParams = NULL;
        if ($content !== NULL) {
            $oldLibrary = $content['library'];
            $oldParams = json_decode($content['params']);
        } else {
            $content = array(
                'disable' => H5PCore::DISABLE_NONE
            );
        }

        // Get library
        $content['library'] = $core->libraryFromString($this->get_input('library'));
        if (!$content['library']) {
            $core->h5pF->setErrorMessage(__('Invalid library.', $this->plugin_slug));
            return FALSE;
        }
        if ($core->h5pF->libraryHasUpgrade($content['library'])) {
            // We do not allow storing old content due to security concerns
            $core->h5pF->setErrorMessage(__('Something unexpected happened. We were unable to save this content.', $this->plugin_slug));
            return FALSE;
        }

        // Check if library exists.
        $content['library']['libraryId'] = $core->h5pF->getLibraryId(
            $content['library']['machineName'],
            $content['library']['majorVersion'],
            $content['library']['minorVersion']
        );
        if (!$content['library']['libraryId']) {
            $core->h5pF->setErrorMessage(__('No such library.', $this->plugin_slug));
            return FALSE;
        }

        // Check parameters
        $content['params'] = $this->get_input('parameters');
        if ($content['params'] === NULL) {
            return FALSE;
        }

        $params = json_decode($content['params']);
        if ($params === NULL) {
            $core->h5pF->setErrorMessage(__('Invalid parameters.', $this->plugin_slug));
            return FALSE;
        }

        $content['params'] = json_encode($params->params);
        $content['metadata'] = $params->metadata;

        // Trim title and check length
        $trimmed_title = empty($content['metadata']->title) ? '' : trim($content['metadata']->title);
        if ($trimmed_title === '') {
            H5P_Plugin_Admin::set_error(sprintf(__('Missing %s.', $this->plugin_slug), 'title'));
            return FALSE;
        }

        if (strlen($trimmed_title) > 255) {
            H5P_Plugin_Admin::set_error(__('Title is too long. Must be 256 letters or shorter.', $this->plugin_slug));
            return FALSE;
        }

        // Set disabled features
        $this->get_disabled_content_features($core, $content);

        try {
            // Save new content
            $content['id'] = $core->saveContent($content);
        } catch (Exception $e) {
            H5P_Plugin_Admin::set_error($e->getMessage());
            return;
        }

        // Move images and find all content dependencies
        $editor = $this->get_h5peditor_instance();
        $editor->processParameters($content['id'], $content['library'], $params->params, $oldLibrary, $oldParams);
        return $content['id'];
    }

    public static function rearrangeContentParams(&$content, $library)
    {
        if ($content['libraryName'] === $library && $library === 'H5P.InteractiveBook') {
            $params = json_decode($content['params']);
            // get chapters with updated params
            $chaptersRearranged = array_filter($params->chapters, function($item) { return property_exists($item, 'chapter'); });
            if (empty($chaptersRearranged)) {
                $chaptersRearranged = array_map(function($chapter) { 
                    return (object) array('chapter' => $chapter, "lockPage" => false); 
                }, $params->chapters);
                $params->chapters = $chaptersRearranged;
                $content['params'] = json_encode($params);
            }
            
            $filtered = json_decode($content['filtered']);
            if ($filtered) {
                if ( !property_exists($filtered, 'chapters') && property_exists($params, 'chapters') ) {
                    $filtered->chapters = $params->chapters;
                }
                // get chapters with updated filtered
                $chaptersRearrangedF = array_filter($filtered->chapters, function($item) { return property_exists($item, 'chapter'); });
                if (empty($chaptersRearrangedF)) {
                    $chaptersRearrangedF = array_map(function($chapter) { 
                        return (object) array('chapter' => $chapter, "lockPage" => false); 
                    }, $filtered->chapters);
                    $filtered->chapters = $chaptersRearrangedF;
                }
                $content['filtered'] = json_encode($filtered);
            }
        }
    }
}
