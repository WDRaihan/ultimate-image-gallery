// click event
;
(function ($) {
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


	// Uploading files
	$(document).on('click', '.uig-gallery-image-upload', function (e) {
		var gallery_image_file_frame;
		e.preventDefault();

		var imageTag = $(this).parents('.uig-field-item').find('.uig-gallery-perview-image');
		var imageUrl = $(this).parent('.uig-image-field-wrappper').find('input[name="uig_gallery_image_url[]"]');

		// If the media frame already exists, reopen it.
		if (gallery_image_file_frame) {
			gallery_image_file_frame.open();
			return;
		}

		// Create the media frame.
		gallery_image_file_frame = wp.media.frames.gallery_image_file_frame = wp.media({
			title: jQuery(this).data('uploader_title'),
			button: {
				text: jQuery(this).data('uploader_button_text'),
			},
			multiple: false // Set to true to allow multiple files to be selected
		});

		// When a file is selected, run a callback.
		gallery_image_file_frame.on('select', function () {
			// We set multiple to false so only get one image from the uploader
			var attachment = gallery_image_file_frame.state().get('selection').first().toJSON();

			var url = attachment.url;

			imageUrl.val(url);
			imageTag.attr('src', url);
		});

		// Finally, open the modal
		gallery_image_file_frame.open();
	});

	//new codes

	//Replace category field name
	function uig_replace_filter_category_name_attr() {
		$("#uig-repeatable-fields .uig-field-item").each(function (index) {
			$('.uig_filter_category_field', this).find('.uig-filter-category').attr('name', 'uig_filter_category[' + index + '][]');
		});
	}
	$(document).ready(function () {
		// Make the field sortable
		$('#uig-repeatable-fields').sortable({
			placeholder: "ui-state-highlight",
			start: function (event, ui) {
				var height = ui.item.height();
				ui.placeholder.height(height);

				uig_replace_filter_category_name_attr();
			},
			stop: function (event, ui) {
				uig_replace_filter_category_name_attr();
			}
		});

		// Add a new field
		$('#uig-add-field').click(function (e) {
			e.preventDefault();
			//$('#uig-repeatable-fields').append($('.uig-field-item-clone').html());
			var original = document.getElementById("uig_field_item_clone");
			var clone = original.cloneNode(true);
			clone.innerHTML = clone.innerHTML.replaceAll("xxx_", "");
			document.getElementById("uig-repeatable-fields").appendChild(clone);
			uig_replace_filter_category_name_attr();
		});

		// Remove a field
		$(document).on('click', '.uig-remove-field', function (e) {
			e.preventDefault();
			$(this).parents('.uig-field-item').remove();
			uig_replace_filter_category_name_attr();
		});
	});

	$(document).on('click', '.toggle-button', function () {
		$(this).toggleClass('arrow-down-style');
		$(this).parents('.uig-field-item').find('.uig-gallery-fields-wrapper').slideToggle();
		$(this).parents('.uig-field-item').toggleClass('pad-bottom-0');
		$(this).parents('.uig-field-item').find('.uig-repeater-action-buttons').toggleClass('border-bottom-0');
	});

	$(document).ready(function () {
		$('.uig_gallery_type').on('change', function () {
			if ($(this).val() == 'filterable_gallery') {
				$('.uig_filter_category_field').removeClass('hidden-if-image-gallery');
				$('.uig_filter_category_field .uig-filter-category').addClass('uig-border-focus');
				setTimeout(function () {
					$('.uig_filter_category_field .uig-filter-category').removeClass('uig-border-focus');
				}, 500);
			} else {
				$('.uig_filter_category_field').addClass('hidden-if-image-gallery');
			}
		});
	});

	//end new codes



	/*Copy shortcode*/
	jQuery('.uig_display_shortcode').on('click', function () {

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