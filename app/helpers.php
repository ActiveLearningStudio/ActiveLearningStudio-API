<?php

if (! function_exists('clone_thumbnail')) {
    /**
     * 
     * @param type $thumbnail
     * @param type $source
     */
    function clone_thumbnail($thumbnail, $source)
    {
        $new_image_url = config('app.default_thumb_url');
        
        if (!empty($thumbnail) && !empty($source)) {
        
            if (filter_var($thumbnail, FILTER_VALIDATE_URL) !== false) {
                return $thumbnail;
            }
            
            $source_file = storage_path("app/public/" . (str_replace('/storage/', '', $thumbnail)));
            if (file_exists($source_file)) {
                $ext = pathinfo(basename($thumbnail), PATHINFO_EXTENSION);
                $new_image_name = uniqid() . '.' . $ext;
                ob_start();
                $destination_file = str_replace("uploads", $source, str_replace(basename($thumbnail), $new_image_name, $source_file));
                \File::copy($source_file, $destination_file);
                ob_get_clean();
                $new_image_url = "/storage/" . $source . "/" . $new_image_name;
            } 
        }
        
        return $new_image_url;
    }
}
