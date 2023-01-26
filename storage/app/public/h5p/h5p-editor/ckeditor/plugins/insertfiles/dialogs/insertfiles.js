CKEDITOR.dialog.add('insertfiles', function (editor) {

    function onChangeSrc() {
        return true;
    }

    var hasFileBrowser = !!(editor.config.filebrowserImageBrowseUrl || editor.config.filebrowserBrowseUrl),
        srcBoxChildren = [
            {
                type: 'text',
                id: 'txtUrl',
                label: editor.lang.insertfiles.url,
                required: true,
                onChange: onChangeSrc,
                validate: CKEDITOR.dialog.validate.notEmpty(editor.lang.insertfiles.alertUrl)
            }
        ];

    return {
        title: "Document Embed",
        width: 400,
        height: 200,

        contents:
            [
                //  document settings tab
                {
                    id: 'settingsTab',
                    label: "Document Embed",
                    elements:
                        [
                            //  textarea
                            {
                                type: 'textarea',
                                id: 'documents',
                                className: 'insertfiles',
                                label: "Instructions",
                                default: 'Different types of documents can be embedded by providing the link to document in below Link field. ' +
                                    '\n\nNote: Microsoft office preview is not available yet.',
                                rows: 4,
                               style: "pointer-events: none;",
                            },
                            //  url
                            {
                                type: 'vbox',
                                padding: 0,
                                children: [
                                    {
                                        type: 'hbox',
                                        widths: ['100%'],
                                        children: srcBoxChildren
                                    }
                                ]
                            },
                            //  options
                            {
                                type: 'hbox',
                                widths: ['60px', '330px'],
                                className: 'insertfiles',
                                children:
                                    [
                                        //  width
                                        {
                                            type: 'text',
                                            width: '45px',
                                            id: 'txtWidth',
                                            label: editor.lang.common.width,
                                            'default': 600,
                                            required: true,
                                            validate: CKEDITOR.dialog.validate.integer(editor.lang.insertfiles.alertWidth)
                                        },
                                        //  height
                                        {
                                            type: 'text',
                                            id: 'txtHeight',
                                            width: '45px',
                                            label: editor.lang.common.height,
                                            'default': 700,
                                            required: true,
                                            validate: CKEDITOR.dialog.validate.integer(editor.lang.insertfiles.alertHeight)
                                        }
                                    ]
                            }
                        ]
                },
            ],
        onOk: function () {
            var dialog = this;
            // create a new div element
            var fileDiv = editor.document.createElement("div");
            var anchor = editor.document.createElement("a");
            var pTag = "<p style='text-align:center'>{ele}</p>"; // (editor.document.createElement("p")).setAttribute('style', 'text-align: center')
            var iframe = editor.document.createElement('iframe');
            var txtUrl = dialog.getValueOf('settingsTab', 'txtUrl');
            var isDocsLink = txtUrl.includes("docs.google.com") || txtUrl.includes("drive.google.com");
            var viewType = "url";
            var regexp = /(ftp|http|https):\/\//;
            if (!regexp.test(txtUrl)) {
                txtUrl = window.location.protocol + '//' + window.location.host + txtUrl;
            }
            var srcEncoded = encodeURIComponent(txtUrl);
            fileDiv.setAttribute('class', 'files');

            if (isDocsLink) {
                try {
                    let lastPart = txtUrl.split('/d/')[1];
                    txtUrl = lastPart.split('/')[0];
                    viewType = "srcid";
                } catch (err) {
                    console.log(err);
                }
            }

            var anchorText = isDocsLink ? false : txtUrl.replace(/^.*[\\\/]/, '').replace(/ /g, "_");
            anchor.appendText(anchorText ? anchorText : "View Doc");
            anchor.setAttribute('href', isDocsLink ? "https://drive.google.com/file/d/" + txtUrl + "/view" : txtUrl);
            anchor.setAttribute('target', '_blank');

            iframe.setAttribute('src', 'https://docs.google.com/viewerng/viewer?embedded=true&pid=explorer&' + viewType + '=' + txtUrl);
            iframe.setAttribute('width', dialog.getValueOf('settingsTab', 'txtWidth'));
            iframe.setAttribute('height', dialog.getValueOf('settingsTab', 'txtHeight'));
            iframe.setAttribute('class', 'insert-iframe');

            fileDiv.appendHtml(pTag.replace("{ele}", anchor.getOuterHtml())); // pTag.append(anchor); pTag1.append(iframe);  fileDiv.append(pTag);  fileDiv.append(pTag1);
            fileDiv.appendHtml(pTag.replace("{ele}", iframe.getOuterHtml()));
            editor.insertElement(fileDiv);
            moveCursorToEnd(editor);
            setTimeout(iframesCheck, 3000);
        }
    };
});


/**
 * Move cusrsor to end
 */
function moveCursorToEnd(editor) {
    var range = editor.createRange();
    range.moveToPosition(range.root, CKEDITOR.POSITION_BEFORE_END);
    editor.getSelection().selectRanges([range]);
}

/**
 * Loop through each IFrame
 */
function iframesCheck() {
    var iframes = $(".cke_wysiwyg_frame").contents().find(".insert-iframe:last-child");
    checkIframeLoaded(iframes[iframes.length - 1]);
}

/**
 * Check if iframe re-loaded skip, otherwise re-load it
 * @param ele
 */
function checkIframeLoaded(ele) {
    var count = 0;
    var intervalID = setInterval(function () {
        if (!isIframeLoaded(ele) && count < 3) {
            var element = $(ele);
            var iframe_url = element.attr("src")
            element.attr("src", iframe_url);
            count++;
            console.log("IFrame ReLoaded...");
            return;
        }
        clearInterval(intervalID);
    }, 1500)

}

/**
 * Quick hack to verify the content of IFrame is Loaded Or NOt
 * Function for checking specific iframe content is loaded or not
 * @param ele
 * @returns {boolean}
 */
function isIframeLoaded(ele) {
    try {
        console.log(ele.contentWindow); // if iframe is loaded this will through exception due to cross-origin policy
        return false; // if here means exception is not thrown so content is not loaded in iframe
    } catch (err) {
        return true;
    }
}
