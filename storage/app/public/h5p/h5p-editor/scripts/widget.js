var H5P = H5P || {};

H5P.jQuery(document).ready(function () {
    if (!window.CKEDITOR) return; // CK not available
    if (CKEDITOR.version < "4.14.1") return; // CK versions is not latest

    // Register plugin with CK
    // Tell H5P about the plugin
    H5PEditor.HtmlAddons = H5PEditor.HtmlAddons || {};

    // Register plugin with CK
    H5PEditor.HtmlAddons.additional = H5PEditor.HtmlAddons.additional || {};

    // custom callback
    H5PEditor.HtmlAddons.additional.additional = function (config, tags) {
        config.removePlugins = 'cloudservices,easyimage,googledocs';
        config.removeButtons = 'Image,Table,Link,Unlink';

        // Print debug to browser console (Ctrl+Shift+J in Chrome)
        console.log('Adding Extra Plugins for Documents Curriki...');

        // config.filebrowserUploadMethod = 'form';
        // URL of a upload script
        config.filebrowserInsertfilesUploadUrl = CKEDITOR.basePath + 'plugins/insertfiles/documentUpload.php?type=files';
        // at this address you can get a list of documents on the server
        // URL where you can get a list of uploaded documents
        config.filebrowserInsertfilesBrowseUrl = CKEDITOR.basePath + 'plugins/insertfiles/documentsList.php?type=files';
        // URL of a upload scriptgi
        // config.filebrowserUploadUrl = CKEDITOR.basePath + 'plugins/imageuploader/imgupload.php';
        // config.filebrowserImageUploadUrl = CKEDITOR.basePath + 'plugins/imageuploader/imgupload.php';
        // at this address you can get a list of documents on the server
        // URL where you can get a list of uploaded documents
        // config.filebrowserBrowseUrl = CKEDITOR.basePath + 'plugins/imageuploader/imgbrowser.php';
        // config.filebrowserImageBrowseUrl = CKEDITOR.basePath + 'plugins/imageuploader/imgbrowser.php';

        // cloud services
        // config.cloudServices_tokenUrl = 'https://example.com/cs-token-endpoint';
        // config.cloudServices_uploadUrl = 'https://your-organization-id.cke-cs.com/easyimage/upload/';

        // Add plugin to config
        config.extraPlugins = (config.extraPlugins ? ',' : '') + 'insertfiles,pastefromgdocs';

        // Looking inside plugin.js I see that InsertFiles should go into
        // the 'insert' toolbar group. So let's create it and add the button
        config.toolbar.push({
            name: 'insert',
            items: ['Insertfiles']
        });

       /* config.toolbar.push({
            name: 'insert',
            items: ['Mathjax']
        });*/

        config.toolbar.push({
            name: 'Math',
            items: ['mathedit']
        });

        console.log(config);

        // Add our special tags
        tags.push('additional');
    };
});
