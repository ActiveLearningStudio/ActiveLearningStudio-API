CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	// %REMOVE_START%
	config.plugins =
		'spreadsheet,' +
		'ajax,' +
		'link,' +
		'undo,' +
		'justify,' +
		'enterkey,' +
		'about,' +
		'basicstyles,' +
		'clipboard,' +
		'elementspath,' +
		'floatingspace,' +
		'htmlwriter,' +
		'removeformat,' +
		'sourcedialog,' +
		'tab,' +
		'toolbar,' +
		'undo,' +
		'resize,' +
		'wysiwygarea,' +
		'contextmenu,' +
		'colorbutton,' +
		'pastefromword,' +
		'pastefromgdocs,' +
		'image,' +
		'format,' +
		'font,' +
		'list,' +
		'magicline,' +
		'maximize,' +
		'print,sourcearea,' +
		'table';
	// %REMOVE_END%

	config.toolbar = [
		{ name: 'styles', items: [ 'Format', 'Font' ] },
		{ name: 'insert', items: [ 'Spreadsheet', 'Table', 'Image' ] },
		{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat' ] },
		{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
		{ name: 'align', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
		{ name: 'links', items: [ 'Link', 'Unlink' ] },
		{ name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent' ] },
		{ name: 'clipboard', items: [ 'Undo', 'Redo' ] },
		{ name: 'document', items: [ 'Print' ] },
		{ name: 'tools', items: [ 'Maximize', 'Source' ] }
	];

	config.height = 550;

	config.extraAllowedContent = 'h1;a[!href]';

	// Plugin location needs to be explicitly provided as it's loaded from outside of CKEditor location.
	var href = location.href.replace( /\/(index.html)?(\/)?$/, '' );

	config.contentsCss = [ config.contentsCss, href + '/css/styles.css' ];

	CKEDITOR.plugins.addExternal( 'spreadsheet', href + '/../' );

};
