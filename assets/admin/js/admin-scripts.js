// click event
;(function ($) {
"use strict";
    
    $('.rai-zoom-gallery-upload-button').click(function (event) { // button click
        // prevent default link click event
        event.preventDefault();

        const button = $(this)
        // we are going to use <input type="hidden"> to store image IDs, comma separated
        const hiddenField = button.prev()
        const hiddenFieldValue = hiddenField.val().split(',')

        const customUploader = wp.media({
            title: 'Insert images',
            library: {
                type: 'image'
            },
            button: {
                text: 'Use these images'
            },
            multiple: true
        }).on('select', function () {

            // get selected images and rearrange the array
            let selectedImages = customUploader.state().get('selection').map(item => {
                item.toJSON();
                return item;
            })

            selectedImages.map(image => {
                // add every selected image to the <ul> list
                $('.rai-zoom-gallery-metabox').append('<li data-id="' + image.id + '"><span class="rai-zoom-gallery-image" style="background-image:url(' + image.attributes.url + ')"></span><a title="Remove" href="#" class="rai-gallery-image-remove">×</a></li>');
                // and to hidden field
                hiddenFieldValue.push(image.id)
            });

            // refresh sortable
            $('.rai-zoom-gallery-metabox').sortable('refresh');
            // add the IDs to the hidden field value
            hiddenField.val(hiddenFieldValue.join());

        }).open();
    });

    // remove image event
    $(document).on('click', '.rai-gallery-image-remove', function (event) {

        event.preventDefault();

        const button = $(this);
        const imageId = button.parent().data('id');
        const container = button.parent().parent();
        const hiddenField = container.parent().find('.rai-image-ids');
        const hiddenFieldValue = hiddenField.val().split(",");
        const i = hiddenFieldValue.indexOf(imageId.toString());
        
        // remove certain array element
        if (i != -1) {
            hiddenFieldValue.splice(i, 1);
        }
        
        button.parent().remove();

        // add the IDs to the hidden field value 
        hiddenField.val(hiddenFieldValue.join());

        // refresh sortable
        container.sortable('refresh');

    });

    // reordering the images with drag and drop
    $('.rai-zoom-gallery-metabox').sortable({
        items: 'li',
        cursor: '-webkit-grabbing', // mouse cursor
        scrollSensitivity: 40,
        /*
        You can set your custom CSS styles while this element is dragging
        start:function(event,ui){
        	ui.item.css({'background-color':'grey'});
        },
        */
        stop: function (event, ui) {
            ui.item.removeAttr('style');

            let sort = new Array() // array of image IDs
            const container = $(this) // .rai-zoom-gallery-metabox

            // each time after dragging we resort our array
            container.find('li').each(function (index) {
                sort.push($(this).attr('data-id'));
            });
            // add the array value to the hidden input field
            
            container.parent().find('.rai-image-ids').val(sort.join());
            // console.log(sort);
        }
    });
    
    
    /*Copy shortcode*/
    jQuery('.rai_display_shortcode').on('click', function(){
    
        var copyText = this;

        if (copyText.value != '') {
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");

            var elem = document.getElementById("rai_shortcode_copied_notice");

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

    });
}(window.jQuery));