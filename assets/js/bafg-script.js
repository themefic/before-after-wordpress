function bafgCopyShortcode() {
    var copyText = document.getElementById("bafg_display_shortcode");

    if (copyText.value != '') {
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");

        var elem = document.getElementById("bafg_copy");
        elem.style.right = '0px';

        var time = 0;
        var id = setInterval(copyAlert, 10);

        function copyAlert() {
            if (time == 200) {
                clearInterval(id);
                elem.style.display = 'none';
            } else {
                time++;
                elem.style.display = 'block';
            }
        }
    }

}

/*
 * Metabox tab script
 */
function bafg_option_tab(evt, cityName) {
    var i, bafg_tabcontent, bafg_tablinks;
    bafg_tabcontent = document.getElementsByClassName("bafg-tabcontent");
    for (i = 0; i < bafg_tabcontent.length; i++) {
        bafg_tabcontent[i].style.display = "none";
    }
    bafg_tablinks = document.getElementsByClassName("bafg-tablinks");
    for (i = 0; i < bafg_tablinks.length; i++) {
        bafg_tablinks[i].className = bafg_tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}

/*
Conditional fields
*/
function bafg_before_after_method_conditional_field(){
    var bafg_before_after_method = jQuery('input:radio[name=bafg_before_after_method]:checked').val();
    if (bafg_before_after_method == 'method_2') {
        jQuery('.bafg-row-before-image, .bafg-row-after-image').hide();
        jQuery('.bafg-row-before-after-image, .bafg_filter_style, .bafg_filter_apply').show();
    } else {
        jQuery('.bafg-row-before-image, .bafg-row-after-image').show();
        jQuery('.bafg-row-before-after-image, .bafg_filter_style, .bafg_filter_apply').hide();
    }
}

function bafg_auto_slide_conditional_field(){
    var bafg_auto_slide = jQuery('input:radio[name=bafg_auto_slide]:checked').val();
    if (bafg_auto_slide == 'true') {
        jQuery('.bafg_move_slider_on_hover').hide();
        jQuery('.bafg_slide_handle').show();
    } else {
        jQuery('.bafg_move_slider_on_hover').show();
        jQuery('.bafg_slide_handle').hide();
    }
}

jQuery(document).ready(function(){
    bafg_before_after_method_conditional_field();
    bafg_auto_slide_conditional_field();
});

jQuery('input:radio[name=bafg_before_after_method]').on('change', function () {
    bafg_before_after_method_conditional_field();
});

jQuery('input:radio[name=bafg_auto_slide]').on('change', function () {
    bafg_auto_slide_conditional_field();
});


// Uploading files
var bafg_before_file_frame;
jQuery('#bafg_before_image_upload').on('click', function (e) {
    e.preventDefault();

    // If the media frame already exists, reopen it.
    if (bafg_before_file_frame) {
        bafg_before_file_frame.open();
        return;
    }

    // Create the media frame.
    bafg_before_file_frame = wp.media.frames.bafg_before_file_frame = wp.media({
        title: jQuery(this).data('uploader_title'),
        button: {
            text: jQuery(this).data('uploader_button_text'),
        },
        multiple: false // Set to true to allow multiple files to be selected
    });

    // When a file is selected, run a callback.
    bafg_before_file_frame.on('select', function () {
        // We set multiple to false so only get one image from the uploader
        attachment = bafg_before_file_frame.state().get('selection').first().toJSON();

        var url = attachment.url;

        //var field = document.getElementById("podcast_file");
        var field = document.getElementById('bafg_before_image');
        var thumbnail = document.getElementById('bafg_before_image_thumbnail');

        field.value = url;
        thumbnail.setAttribute('src',url);
    });

    // Finally, open the modal
    bafg_before_file_frame.open();
});


var bafg_after_file_frame;
jQuery('#bafg_after_image_upload').on('click', function (e) {
    e.preventDefault();

    // If the media frame already exists, reopen it.
    if (bafg_after_file_frame) {
        bafg_after_file_frame.open();
        return;
    }

    // Create the media frame.
    bafg_after_file_frame = wp.media.frames.bafg_after_file_frame = wp.media({
        title: jQuery(this).data('uploader_title'),
        button: {
            text: jQuery(this).data('uploader_button_text'),
        },
        multiple: false // Set to true to allow multiple files to be selected
    });

    // When a file is selected, run a callback.
    bafg_after_file_frame.on('select', function () {
        // We set multiple to false so only get one image from the uploader
        attachment = bafg_after_file_frame.state().get('selection').first().toJSON();

        var url = attachment.url;
    
        var field = document.getElementById('bafg_after_image');
        var thumbnail = document.getElementById('bafg_after_image_thumbnail');

        field.value = url;
        thumbnail.setAttribute('src',url);
    });

    // Finally, open the modal
    bafg_after_file_frame.open();
});

/*
Color picker
*/
jQuery('.bafg-color-field').each(function () {
    jQuery(this).wpColorPicker();
});