;(function ($) {
    'use strict';

    // View a list of images
    $('#uig_gallery_images').viewer({
        inline: false,
        scalable: false,
        rotatable: false,
        movable: true,
		maxZoomRatio: 3,
    });
    
	//Wrap media buttons
    jQuery(document).on('click','.uig-gallery-item',function(){
        if ( jQuery('.toolbar-top-buttons').length < 1 ) {
          jQuery('li.viewer-prev, li.viewer-play, li.viewer-next').wrapAll('<div class="toolbar-top-buttons"></div>');
        }
    });
    
	//Filter scripts
    jQuery('.uig-filter-buttons button').on('click', function(){
		
		jQuery('.uig-filter-buttons button').removeClass('uig-filter-active');
		jQuery(this).addClass('uig-filter-active');
		
        var category = jQuery(this).attr('data-filter');
        
        if( category == '*' ){
            jQuery('.uig-img-viewer #uig_gallery_images li').show();
            
            jQuery('.uig-img-viewer #uig_gallery_images li').removeClass('hide-item');
        }else{
            
            var hasclass = $( '.uig-img-viewer #uig_gallery_images li' ).hasClass('hide-item');
            
            jQuery('.uig-img-viewer #uig_gallery_images li').addClass('hide-item');
            
            jQuery('.uig-img-viewer #uig_gallery_images li').addClass('fadeout');
            
            jQuery('.uig-img-viewer #uig_gallery_images li.'+category+'').show();
            jQuery('.uig-img-viewer #uig_gallery_images li.'+category+'').removeClass('hide-item');
            
            if (!hasclass) {
                
                setTimeout(function(){
                    jQuery('.uig-img-viewer #uig_gallery_images li.hide-item').hide();
                    jQuery('.uig-img-viewer #uig_gallery_images li').removeClass('fadeout');
                }, 150);
                
            } else {
                
                jQuery('.uig-img-viewer #uig_gallery_images li.hide-item').hide();
                jQuery('.uig-img-viewer #uig_gallery_images li').removeClass('fadeout');
            }
            
        }
    });

})(jQuery);
