// Copyright (c) 2020, Curriki Studio - AHMAD MUKHTAR. All rights reserved.
// For licensing, see LICENSE.md

CKEDITOR.plugins.add( 'imageuploader', {
    init: function( editor ) {
        editor.config.filebrowserBrowseUrl = 'ckeditor/plugins/imageuploader/imgbrowser.php';
    }
});
