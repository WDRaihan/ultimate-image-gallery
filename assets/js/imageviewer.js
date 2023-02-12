;(function ($) {
    'use strict';

    var $image = $('#image');

    /*$image.viewer({
        inline: false,
        scalable: false,
        rotatable: false,
        viewed: function () {
            $image.viewer('zoomTo', 1);
        }
    });*/

    // View a list of images
    $('#images').viewer({
        inline: false,
        scalable: false,
        rotatable: false,
        movable: true,
		url: 'hs',
    });
    
    // Get the Viewer.js instance after initialized
    //var viewer = $image.data('viewer');
    
    jQuery(document).on('click','.uig-gallery-item',function(){
        if ( jQuery('.toolbar-top-buttons').length < 1 ) {
          jQuery('li.viewer-prev, li.viewer-play, li.viewer-next').wrapAll('<div class="toolbar-top-buttons"></div>');
        }
    });
    
    jQuery('.uig-filter-buttons button').on('click', function(){
		
		jQuery('.uig-filter-buttons button').removeClass('uig-filter-active');
		jQuery(this).addClass('uig-filter-active');
		
        var category = jQuery(this).attr('data-filter');
        
        if( category == '*' ){
            jQuery('.img-viewer #images li').show();
            
            jQuery('.img-viewer #images li').removeClass('hide-item');
        }else{
            
            var hasclass = $( '.img-viewer #images li' ).hasClass('hide-item');
            
            jQuery('.img-viewer #images li').addClass('hide-item');
            
            jQuery('.img-viewer #images li').addClass('fadeout');
            
            jQuery('.img-viewer #images li.'+category+'').show();
            jQuery('.img-viewer #images li.'+category+'').removeClass('hide-item');
            
            if (!hasclass) {
                
                setTimeout(function(){
                    jQuery('.img-viewer #images li.hide-item').hide();
                    jQuery('.img-viewer #images li').removeClass('fadeout');
                }, 150);
                
            } else {
                
                jQuery('.img-viewer #images li.hide-item').hide();
                jQuery('.img-viewer #images li').removeClass('fadeout');
            }
            
        }
    });
    
    /*jQuery(document).ready(function(){
		
		jQuery( '.uig-filter-gallery-wrapper' ).each(function(){
			
			var eachWraper = jQuery(this);

			var eachItem = jQuery( '.uig-gallery-item', this );

			jQuery( '.uig_gallery_filter', eachWraper ).isotope({ filter: '*' });

			eachWraper.find( '.uig-filter-buttons' ).on( 'click', '.uig-filter-button', function(){

				var filterValue = jQuery(this).attr('data-filter');

				eachWraper.find( '.uig_gallery_filter' ).isotope({ filter: filterValue });

			} );

		});
        
        //jQuery('.uig-filter-button.uig-filter-active').trigger('click');
	
	});*/

})(jQuery);
