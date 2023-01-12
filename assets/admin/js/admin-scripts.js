// click event
;(function ($) {
"use strict";
    
    $('.uig-zoom-gallery-upload-button').click(function (event) { // button click
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
                $('.uig-zoom-gallery-metabox').append('<li data-id="' + image.id + '"><span class="uig-zoom-gallery-image" style="background-image:url(' + image.attributes.url + ')"></span><a title="Remove" href="#" class="uig-gallery-image-remove">×</a></li>');
                // and to hidden field
                hiddenFieldValue.push(image.id)
            });

            // refresh sortable
            $('.uig-zoom-gallery-metabox').sortable('refresh');
            // add the IDs to the hidden field value
            hiddenField.val(hiddenFieldValue.join());

        }).open();
    });

    // remove image event
    $(document).on('click', '.uig-gallery-image-remove', function (event) {

        event.preventDefault();

        const button = $(this);
        const imageId = button.parent().data('id');
        const container = button.parent().parent();
        const hiddenField = container.parent().find('.uig-image-ids');
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
    $('.uig-zoom-gallery-metabox').sortable({
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
            const container = $(this) // .uig-zoom-gallery-metabox

            // each time after dragging we resort our array
            container.find('li').each(function (index) {
                sort.push($(this).attr('data-id'));
            });
            // add the array value to the hidden input field
            
            container.parent().find('.uig-image-ids').val(sort.join());
            // console.log(sort);
        }
    });
    

    //new codes
    
      $(document).ready(function () {
          // Make the field sortable
          $('#repeatable-field').sortable();

          // Add a new field
          $('#add-field').click(function (e) {
              e.preventDefault();
              $('#repeatable-field').append($('.field-item-clone').html());
          });

          // Remove a field
          $(document).on('click', '.remove-field', function (e) {
              e.preventDefault();
              $(this).parents('.field-item').remove();
          });
      });
    
    $(document).on('click', '.toggle-button', function() {
        $(this).toggleClass('arrow-down-style');
        $(this).parents('.field-item').find('.gallery-fields-wrapper').slideToggle();
        $(this).parents('.field-item').toggleClass('pad-bottom-0');
        $(this).parents('.field-item').find('.repeater-action-buttons').toggleClass('border-bottom-0');
    });
    //end new codes
    
    
    
    /*Copy shortcode*/
    jQuery('.uig_display_shortcode').on('click', function(){
    
        var copyText = this;

        if (copyText.value != '') {
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");

            var elem = document.getElementById("uig_shortcode_copied_notice");

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