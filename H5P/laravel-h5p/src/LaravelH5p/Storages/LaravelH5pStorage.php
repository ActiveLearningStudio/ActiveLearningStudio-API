<?php

/*
 *
 * @Project        Expression project.displayName is undefined on line 5, column 35 in Templates/Licenses/license-default.txt.
 * @Copyright      Djoudi
 * @Created        2017-02-11
 * @Filename       LaravelH5pStorage.php
 * @Description
 *
 */

namespace Djoudi\LaravelH5p\Storages;

use Exception;
use H5peditorFile;
use H5PFileStorage;

class LaravelH5pStorage implements H5PFileStorage
{
    private $path, $altEditorPath;

    /**
     * The great Constructor!
     *
     * @param string $path
     *  The base location of H5P files
     * @param $altEditorPath
     *  Optional. Use a different editor path
     */
    function __construct($path, $altEditorPath = NULL)
    {
        // Set H5P storage path
        $this->path = $path;
        $this->altEditorPath = $altEditorPath;
    }

    /**
     * Store the library folder.
     *
     * @param array $library
     *  Library properties
     * @throws Exception
     */
    public function saveLibrary($library)
    {
        $dest = $this->path . '/libraries/' . \H5PCore::libraryToString($library, TRUE);

        // Make sure destination dir doesn't exist
        \H5PCore::deleteFileTree($dest);

        // Move library folder
        self::copyFileTree($library['uploadDirectory'], $dest);
    }

    /**
     * Store the content folder.
     *
     * @param string $source
     *  Path on file system to content directory.
     * @param array $content
     *  Content properties
     * @throws Exception
     */
    public function saveContent($source, $content)
    {
        $dest = "{$this->path}/content/{$content['id']}";
        $dest_editor = "{$this->path}/editor/images";
        $dest_editor_videos = "{$this->path}/editor/videos";
        $dest_editor_audios = "{$this->path}/editor/audios";

        // Remove any old content
        \H5PCore::deleteFileTree($dest);

        self::copyFileTree($source, $dest);
        self::copyFileTree($source . '/images', $dest_editor);
        self::copyFileTree($source . '/videos', $dest_editor_videos);
        self::copyFileTree($source . '/audios', $dest_editor_audios);
    }

    /**
     * Remove content folder.
     *
     * @param array $content
     *  Content properties
     */
    public function deleteContent($content)
    {
        \H5PCore::deleteFileTree("{$this->path}/content/{$content['id']}");
    }

    /**
     * Creates a stored copy of the content folder.
     *
     * @param string $id
     *  Identifier of content to clone.
     * @param int $newId
     *  The cloned content's identifier
     * @throws Exception
     */
    public function cloneContent($id, $newId)
    {
        $path = $this->path . '/content/';
        if (file_exists($path . $id)) {
            self::copyFileTree($path . $id, $path . $newId);
        }
    }

    /**
     * Get path to a new unique tmp folder.
     *
     * @return string
     */
    public function getTmpPath()
    {
        $temp = "{$this->path}/temp";
        self::dirReady($temp);
        return "{$temp}/" . uniqid('h5p-');
    }

    /**
     * Fetch content folder and save in target directory.
     *
     * @param int $id
     *  Content identifier
     * @param string $target
     *  Where the content folder will be saved
     * @throws Exception
     */
    public function exportContent($id, $target)
    {
        $source = "{$this->path}/content/{$id}";
        if (file_exists($source)) {
            // Copy content folder if it exists
            self::copyFileTree($source, $target);
        } else {
            // No content folder, create empty dir for content.json
            self::dirReady($target);
        }
    }

    /**
     * Fetch library folder and save in target directory.
     *
     * @param array $library
     *  Library properties
     * @param string $target
     *  Where the library folder will be saved
     * @param $developmentPath
     *  Folder that library resides in
     * @throws Exception
     */
    public function exportLibrary($library, $target, $developmentPath = NULL)
    {
        $folder = \H5PCore::libraryToString($library, TRUE);
        $srcPath = ($developmentPath === NULL ? "/libraries/{$folder}" : $developmentPath);
        self::copyFileTree("{$this->path}{$srcPath}", "{$target}/{$folder}");
    }

    /**
     * Save export in file system
     *
     * @param string $source
     *  Path on file system to temporary export file.
     * @param string $filename
     *  Name of export file.
     * @throws Exception Unable to save the file
     */
    public function saveExport($source, $filename)
    {
        $this->deleteExport($filename);

        if (!self::dirReady("{$this->path}/exports")) {
            throw new Exception('Unable to create directory for H5P export file.');
        }

        if (!copy($source, "{$this->path}/exports/{$filename}")) {
            throw new Exception('Unable to save H5P export file.');
        }
    }

    /**
     * Removes given export file
     *
     * @param string $filename
     */
    public function deleteExport($filename)
    {
        $target = "{$this->path}/exports/{$filename}";
        if (file_exists($target)) {
            unlink($target);
        }
    }

    /**
     * Check if the given export file exists
     *
     * @param string $filename
     * @return boolean
     */
    public function hasExport($filename)
    {
        $target = "{$this->path}/exports/{$filename}";
        return file_exists($target);
    }

    /**
     * Will concatenate all JavaScrips and Stylesheets into two files in order
     * to improve page performance.
     *
     * @param array $files
     *  A set of all the assets required for content to display
     * @param string $key
     *  Hashed key for cached asset
     */
    public function cacheAssets(&$files, $key)
    {
        foreach ($files as $type => $assets) {
            if (empty($assets)) {
                continue; // Skip no assets
            }

            $content = '';
            foreach ($assets as $asset) {
                // Get content from asset file
                $assetContent = file_get_contents($this->path . $asset->path);
                $cssRelPath = preg_replace('/[^\/]+$/', '', $asset->path);

                // Get file content and concatenate
                if ($type === 'scripts') {
                    $content .= $assetContent . ';\n';
                } else {
                    // Rewrite relative URLs used inside stylesheets
                    $content .= preg_replace_callback(
                        '/url\([\'"]?([^"\')]+)[\'"]?\)/i',
                        function ($matches) use ($cssRelPath) {
                            if (preg_match("/^(data:|([a-z0-9]+:)?\/)/i", $matches[1]) === 1) {
                                return $matches[0]; // Not relative, skip
                            }
                            return 'url("../' . $cssRelPath . $matches[1] . '")';
                        },
                        $assetContent
                    ) . '\n';
                }
            }

            self::dirReady("{$this->path}/cachedassets");
            $ext = ($type === 'scripts' ? 'js' : 'css');
            $output_file = "/cachedassets/{$key}.{$ext}";
            file_put_contents($this->path . $output_file, $content);
            $files[$type] = array((object) array(
                'path' => $output_file,
                'version' => '',
            ));
        }
    }

    /**
     * Will check if there are cache assets available for content.
     *
     * @param string $key
     *  Hashed key for cached asset
     * @return array
     */
    public function getCachedAssets($key)
    {
        $files = array();

        $js = "/cachedassets/{$key}.js";
        if (file_exists($this->path . $js)) {
            $files['scripts'] = array((object)array(
                'path' => $js,
                'version' => '',
            ));
        }

        $css = "/cachedassets/{$key}.css";
        if (file_exists($this->path . $css)) {
            $files['styles'] = array((object)array(
                'path' => $css,
                'version' => '',
            ));
        }

        return empty($files) ? NULL : $files;
    }

    /**
     * Remove the aggregated cache files.
     *
     * @param array $keys
     *   The hash keys of removed files
     */
    public function deleteCachedAssets($keys)
    {
        foreach ($keys as $hash) {
            foreach (array('js', 'css') as $ext) {
                $path = "{$this->path}/cachedassets/{$hash}.{$ext}";
                if (file_exists($path)) {
                    unlink($path);
                }
            }
        }
    }

    /**
     * Read file content of given file and then return it.
     *
     * @param string $file_path
     * @return string
     */
    public function getContent($file_path)
    {
        return file_get_contents($file_path);
    }

    /**
     * Save files uploaded through the editor.
     * The files must be marked as temporary until the content form is saved.
     *
     * @param H5peditorFile $file
     * @param int $contentId
     * @return H5peditorFile
     */
    public function saveFile($file, $contentId)
    {
        // Prepare directory
        if (empty($contentId)) {
            // Should be in editor tmp folder
            $path = $this->getEditorPath();
        } else {
            // Should be in content folder
            $path = $this->path . '/content/' . $contentId;
        }
        $path .= '/' . $file->getType() . 's';

        self::dirReady($path);

        // Add filename to path
        $path .= '/' . $file->getName();

        if (method_exists($file, 'getData')) {
            $fileData = $file->getData();
            if ($fileData) {
                file_put_contents($path, $fileData);
            }
        } else {
            copy($_FILES['file']['tmp_name'], $path);
        }

        return $file;
    }

    /**
     * Copy a file from another content or editor tmp dir.
     * Used when copy pasting content in H5P Editor.
     *
     * @param string $file path + name
     * @param $fromId
     * @param $toId
     */
    public function cloneContentFile($file, $fromId, $toId)
    {
        // Determine source path
        if ($fromId === 'editor') {
            $source_path = $this->getEditorPath();
        } else {
            $source_path = "{$this->path}/content/{$fromId}";
        }
        $source_path .= '/' . $file;

        // Determine target path
        $filename = basename($file);
        $file_dir = str_replace($filename, '', $file);
        $target_path = "{$this->path}/content/{$toId}/{$file_dir}";

        // Make sure it's ready
        self::dirReady($target_path);

        $target_path .= $filename;

        // Check to see if source exist and if target doesn't
        if (!file_exists($source_path) || file_exists($target_path)) {
            return; // Nothing to copy from or target already exists
        }

        copy($source_path, $target_path);
    }

    /**
     * Copy a content from one directory to another. Defaults to cloning
     * content from the current temporary upload folder to the editor path.
     *
     * @param string $source path to source directory
     * @param $contentId
     *
     * @return object Object containing h5p json and content json data
     * @throws Exception
     */
    public function moveContentDirectory($source, $contentId = NULL)
    {
        if ($source === NULL) {
            return NULL;
        }

        if ($contentId === NULL || $contentId == 0) {
            $target = $this->getEditorPath();
        } else {
            // Use content folder
            $target = "{$this->path}/content/{$contentId}";
        }

        $contentSource = $source . DIRECTORY_SEPARATOR . 'content';
        $contentFiles = array_diff(scandir($contentSource), array('.', '..', 'content.json'));
        foreach ($contentFiles as $file) {
            if (is_dir("{$contentSource}/{$file}")) {
                self::copyFileTree("{$contentSource}/{$file}", "{$target}/{$file}");
            } else {
                copy("{$contentSource}/{$file}", "{$target}/{$file}");
            }
        }
    }

    /**
     * Checks to see if content has the given file.
     * Used when saving content.
     *
     * @param string $file path + name
     * @param int $contentId
     * @return string File ID or NULL if not found
     */
    public function getContentFile($file, $contentId)
    {
        $path = "{$this->path}/content/{$contentId}/{$file}";
        return file_exists($path) ? $path : NULL;
    }

    /**
     * Checks to see if content has the given file.
     * Used when saving content.
     *
     * @param string $file path + name
     * @param int $contentId
     */
    public function removeContentFile($file, $contentId)
    {
        $path = "{$this->path}/content/{$contentId}/{$file}";
        if (file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * Check if server setup has write permission to
     * the required folders
     *
     * @return bool True if site can write to the H5P files folder
     */
    public function hasWriteAccess()
    {
        return self::dirReady($this->path);
    }

    /**
     * Recursive function for copying directories.
     *
     * @param string $source
     *  From path
     * @param string $destination
     *  To path
     *
     * @throws Exception
     */
    private static function copyFileTree($source, $destination)
    {
        if (!self::dirReady($destination)) {
            throw new Exception('unabletocopy');
        }

        $ignoredFiles = self::getIgnoredFiles("{$source}/.h5pignore");

        $dir = opendir($source);
        if ($dir === FALSE) {
            trigger_error('Unable to open directory ' . $source, E_USER_WARNING);
            throw new Exception('unabletocopy');
        }

        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..') && $file != '.git' && $file != '.gitignore' && !in_array($file, $ignoredFiles)) {
                if (is_dir("{$source}/{$file}")) {
                    self::copyFileTree("{$source}/{$file}", "{$destination}/{$file}");
                } else {
                    copy("{$source}/{$file}", "{$destination}/{$file}");
                }
            }
        }

        closedir($dir);
    }

    /**
     * Retrieve array of file names from file.
     *
     * @param string $file
     * @return array Array with files that should be ignored
     */
    private static function getIgnoredFiles($file)
    {
        if (file_exists($file) === FALSE) {
            return array();
        }

        $contents = file_get_contents($file);
        if ($contents === FALSE) {
            return array();
        }

        return preg_split('/\s+/', $contents);
    }

    /**
     * Recursive function that makes sure the specified
     * directory exists and is writable.
     *
     * @param string $path
     * @return bool
     */
    private static function dirReady($path)
    {
        if (!file_exists($path)) {
            $parent = preg_replace("/\/[^\/]+\/?$/", '', $path);
            if (!self::dirReady($parent)) {
                return FALSE;
            }

            mkdir($path, 0777, true);
        }

        if (!is_dir($path)) {
            trigger_error('Path is not a directory ' . $path, E_USER_WARNING);
            return FALSE;
        }

        if (!is_writable($path)) {
            trigger_error('Unable to write to ' . $path . ' – check directory permissions –', E_USER_WARNING);
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Easy helper function for retrieving the editor path
     *
     * @return string Path to editor files
     */
    private function getEditorPath()
    {
        return $this->path . '/editor';
        // return ($this->altEditorPath !== NULL ? $this->altEditorPath : "{$this->path}/editor");
    }

    public function hasPresave($libraryName, $developmentPath = null)
    {
        return false;
    }

    /**
     * Check if upgrades script exist for library.
     *
     * @param string $machineName
     * @param int $majorVersion
     * @param int $minorVersion
     * @return string Relative path
     */
    public function getUpgradeScript($machineName, $majorVersion, $minorVersion)
    {
        $upgrades = "/libraries/{$machineName}-{$majorVersion}.{$minorVersion}/upgrades.js";
        if (file_exists($this->path . $upgrades)) {
            return $upgrades;
        } else {
            return NULL;
        }
    }

    public function saveFileFromZip($path, $file, $stream)
    {
        $filePath = $path . '/' . $file;

        // Make sure the directory exists first
        $matches = array();
        preg_match('/(.+)\/[^\/]*$/', $filePath, $matches);
        self::dirReady($matches[1]);

        // Store in local storage folder
        return file_put_contents($filePath, $stream);
    }
}
