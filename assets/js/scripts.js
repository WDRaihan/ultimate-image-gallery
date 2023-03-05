;(function ($) {
	'use strict';

	jQuery('.uig-img-viewer').each(function () {
		var thisParent = jQuery(this);
		var GalleryId = jQuery(this).attr('gallery_id');
		var parentClass = '.uig-img-viewer-'+GalleryId;

		// View a list of images
		$(''+parentClass+' .uig_gallery_images').viewer({
			inline: false,
			scalable: false,
			rotatable: false,
			movable: true,
			maxZoomRatio: 3,
		});

		//Filter scripts
		jQuery(''+parentClass+' .uig-filter-buttons button').on('click', function () {

			jQuery(''+parentClass+' .uig-filter-buttons button').removeClass('uig-filter-active');
			jQuery(this).addClass('uig-filter-active');

			var category = jQuery(this).attr('data-filter');

			if (category == '*') {
				jQuery(''+parentClass+' .uig_gallery_images li').show();

				jQuery(''+parentClass+' .uig_gallery_images li').removeClass('hide-item');
			} else {

				var hasclass = $(''+parentClass+' .uig_gallery_images li').hasClass('hide-item');

				jQuery(''+parentClass+' .uig_gallery_images li').addClass('hide-item');

				jQuery(''+parentClass+' .uig_gallery_images li').addClass('fadeout');

				jQuery(''+parentClass+' .uig_gallery_images li.' + category + '').show();
				jQuery(''+parentClass+' .uig_gallery_images li.' + category + '').removeClass('hide-item');

				if (!hasclass) {

					setTimeout(function () {
						jQuery(''+parentClass+' .uig_gallery_images li.hide-item').hide();
						jQuery(''+parentClass+' .uig_gallery_images li').removeClass('fadeout');
					}, 150);

				} else {

					jQuery(''+parentClass+' .uig_gallery_images li.hide-item').hide();
					jQuery(''+parentClass+' .uig_gallery_images li').removeClass('fadeout');
				}

			}
		});

	});
	
	//Wrap media buttons
	jQuery(document).on('click', '.uig-gallery-item', function () {
		jQuery('.viewer-container').each(function(){
			if (jQuery('.toolbar-top-buttons', this).length < 1) {
				jQuery('li.viewer-prev, li.viewer-play, li.viewer-next', this).wrapAll('<div class="toolbar-top-buttons"></div>');
			}
		});

	});

})(jQuery);