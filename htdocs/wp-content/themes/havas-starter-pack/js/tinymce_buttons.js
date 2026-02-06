(function () {
    tinymce.PluginManager.add('cta', function (editor, url) {
        editor.addButton('cta', {
            text: 'CTA',
            icon: false,
            onclick: function () {
                editor.windowManager.open({
                    title: 'Insert CTA',
                    body: [
                        {
                            type: 'textbox',
                            name: 'img',
                            label: 'Image (64x64 pixels)',
                            value: '',
                            classes: 'my_input_image',
                        },
                        {
                            type: 'button',
                            name: 'my_upload_button',
                            label: '',
                            text: 'Upload image',
                            classes: 'my_upload_button',
                        },
                        {
                            type: 'textbox',
                            name: 'text',
                            label: 'Text',
                            value: '',
                            multiline: true,
                            classes: 'my_input_text',
                        },
                        {
                            type: 'textbox',
                            name: 'link',
                            label: 'Link',
                            value: '',
                            classes: 'my_input_link',
                        },
                        {
                            type: 'button',
                            name: 'my_add_edit_link_button',
                            label: '',
                            text: 'Add/Edit link',
                            classes: 'my_add_edit_link_button',
                        },
                        {
                            type: 'checkbox',
                            name: 'target',
                            label: 'Open in new window?',
                            text: '',
                        },
                    ],
                    onsubmit: function (e) {
                        var html = '';
                        var target = '';

                        if (e.data.target) {
                            target = ' target="_blank"';
                        }

                        html = '<a href="' + e.data.link + '" class="pageint_imgText_link"' + target + '><img src="' + e.data.img + '"><span class="pageint_imgText_link_text">' + e.data.text.replace(/(?:\r\n|\r|\n)/g, '<br />') + '</span></a>';

                        editor.insertContent(html);
                    }
                });
            },
        });
    });

})();

jQuery(document).ready(function ($) {
    acf.addAction('wysiwyg_tinymce_init', function( ed, id, mceInit, field ){
        // ed (object) tinymce object returned by the init function
        // id (string) identifier for the tinymce instance
        // mceInit (object) args given to the tinymce function
        // field (object) field instance
        console.log(ed);
        console.log(id);
        console.log(mceInit);
        console.log(field);
    });

    var _link_sideload = false;

    $(document).on('click', '.mce-my_upload_button', upload_image_tinymce);
    $(document).on('click', '.mce-my_add_edit_link_button', add_edit_link_button_tinymce);
    $(document).on('click', '#wp-link-submit', function (event) {
        var linkAtts = wpLink.getAttrs();
        var link_val_container = $('.mce-my_input_link');
        link_val_container.val(linkAtts.href);
        _removeLinkListeners();
        return false;
    });

    $(document).on('click', '#wp-link-cancel', function (event) {
        _removeLinkListeners();
        return false;
    });

    /* LINK EDITOR EVENT HACKS
-------------------------------------------------------------- */
    function _removeLinkListeners() {
        if (_link_sideload) {
            if (typeof wpActiveEditor !== undefined) {
                wpActiveEditor = undefined;
            }
        }

        wpLink.close();
        wpLink.textarea = $('html');//focus on document

        $(document).off('click', '#wp-link-submit');
        $(document).off('click', '#wp-link-cancel');
    }

    function upload_image_tinymce(e) {
        e.preventDefault();
        var $input_field = $('.mce-my_input_image');
        var custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Add Image',
            button: {
                text: 'Add Image'
            },
            multiple: false
        });
        custom_uploader.on('select', function () {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            $input_field.val(attachment.url);
        });
        custom_uploader.open();
    }

    function add_edit_link_button_tinymce(e) {
        e.preventDefault();
        var $input_field = $('.mce-my_input_link');
        wpActiveEditor = true; //we need to override this var as the link dialogue is expecting an actual wp_editor instance
        _link_sideload = true;
        wpLink.open($input_field.attr('id')); //open the link popup
        return false;
    }
});