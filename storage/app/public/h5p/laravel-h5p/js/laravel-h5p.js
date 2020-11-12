var ns = H5PEditor;

(function ($) {
  H5PEditor.init = function () {

    //set auth token for subsequent ajax calls
    const auth_token = localStorage.getItem("auth_token")
    if(auth_token){
      H5P.jQuery.ajaxSetup({
          beforeSend: function (xhr)
          {
            xhr.setRequestHeader("Authorization", "Bearer " + auth_token);        
          }
      });
    }
    
    H5PEditor.$ = H5P.jQuery;
    H5PEditor.basePath = H5PIntegration.editor.libraryUrl;
    H5PEditor.fileIcon = H5PIntegration.editor.fileIcon;
    H5PEditor.ajaxPath = H5PIntegration.editor.ajaxPath;
    H5PEditor.filesPath = H5PIntegration.editor.filesPath;
    H5PEditor.apiVersion = H5PIntegration.editor.apiVersion;
    H5PEditor.contentLanguage = H5PIntegration.editor.language;

    // Semantics describing what copyright information can be stored for media.
    H5PEditor.copyrightSemantics = H5PIntegration.editor.copyrightSemantics;
    H5PEditor.metadataSemantics = H5PIntegration.editor.metadataSemantics;

    // Required styles and scripts for the editor
    H5PEditor.assets = H5PIntegration.editor.assets;

    // Required for assets
    H5PEditor.baseUrl = '';

    if (H5PIntegration.editor.nodeVersionId !== undefined) {
      H5PEditor.contentId = H5PIntegration.editor.nodeVersionId;
    }

    var h5peditor;
    var $upload = $('.laravel-h5p-upload').parents('.laravel-h5p-upload-container');
    var $editor = $('#laravel-h5p-editor');
    var $create = $('#laravel-h5p-create').hide();
    var $type = $('.laravel-h5p-type');
    var $params = $('#laravel-h5p-parameters');
    var $library = $('#laravel-h5p-library');
    var library = $library.val();

    $type.change(function () {
      if ($type.filter(':checked').val() === 'upload') {
        $create.hide();
        $upload.show();
      }
      else {
        $upload.hide();
        if (h5peditor === undefined && $editor[0] != undefined && $params.val() != undefined) {
         
          window.h5peditorCopy = h5peditor = new ns.Editor(library, $params.val(), $editor[0]);
        }
        $create.show();
      }
    });

    if ($type.filter(':checked').val() === 'upload') {
      $type.change();
    }
    else {
      $type.filter('input[value="create"]').attr('checked', true).change();
    }

    let formIsUpdated = false;
    const $form = $('#laravel-h5p-form').submit(function (event) {
      if ($type.length && $type.filter(':checked').val() === 'upload') {
        return; // Old file upload
      }

      if (h5peditor !== undefined && !formIsUpdated) {

        // Get content from editor
        h5peditor.getContent(function (content) {

          // Set main library
          $library.val(content.library);

          // Set params
          $params.val(content.params);

          // Submit form data
          formIsUpdated = true;
          $form.submit();
        });

        // Stop default submit
        event.preventDefault();
      }
    });

    // Title label
    var $title = $('#laravel-h5p-title');
    var $label = $title.prev();
    $title.focus(function () {
      $label.addClass('screen-reader-text');
    }).blur(function () {
      if ($title.val() === '') {
        $label.removeClass('screen-reader-text');
      }
    }).focus();

    // Delete confirm
    $('#laravel-h5p-destory').click(function () {
      return confirm(H5PIntegration.editor.deleteMessage);
    });

  };

  H5PEditor.getAjaxUrl = function (action, parameters) {

    var url = H5PIntegration.editor.ajaxPath + action + '?';    

    if (parameters !== undefined) {
      for (var property in parameters) {
        if (parameters.hasOwnProperty(property)) {
          url += '&' + property + '=' + parameters[property];
        }
      }
    }

    return url;
  };

  $(document).ready(H5PEditor.init);
})(H5P.jQuery);