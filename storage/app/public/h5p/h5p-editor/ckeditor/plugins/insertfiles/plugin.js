//  check whether jQuery is loaded
if( !window.jQuery ) {
  //  if not - load jQuery
  var jq = document.createElement( 'script' );
  jq.type = 'text/javascript';
  jq.src = 'https://code.jquery.com/jquery-2.0.3.min.js';
  document.getElementsByTagName('head')[0].appendChild(jq);
}

CKEDITOR.plugins.add('insertfiles', {
  hidpi: true,
  icons: 'insertfiles',
  lang: 'en,ru',

  init: function(editor) {
    editor.addCommand('insertfiles', new CKEDITOR.dialogCommand('insertfiles'));

    editor.ui.addButton('Insertfiles', {
      label: editor.lang.insertfiles.button,
      command: 'insertfiles',
      toolbar: 'insert'
    });

    CKEDITOR.document.appendStyleSheet( CKEDITOR.getUrl( this.path + 'dialogs/insertfiles.css' ) );
    CKEDITOR.dialog.add('insertfiles', this.path + 'dialogs/insertfiles.js');
  }
});
