;(function ($) {
    'use strict';

    var $image = $('#image');

    $image.viewer({
        inline: false,
        viewed: function () {
            $image.viewer('zoomTo', 1);
        }
    });

    // Get the Viewer.js instance after initialized
    var viewer = $image.data('viewer');

    // View a list of images
    $('#images').viewer();
    
    
    jQuery('.rai-filter-buttons button').on('click', function(){
        var category = jQuery(this).attr('data-filter');
        
        if( category == '*' ){
            jQuery('.img-viewer #images li').show();
            
            jQuery('.img-viewer #images li').removeClass('hide-item');
        }else{
            
            var hasclass = $( '.img-viewer #images li' ).hasClass('hide-item')
            
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
		
		jQuery( '.rai-filter-gallery-wraper' ).each(function(){
			
			var eachWraper = jQuery(this);

			var eachItem = jQuery( '.rai-filter-item', this );

			jQuery( '.rai_gallery_filter', eachWraper ).isotope({ filter: '*' });

			eachWraper.find( '.rai-filter-buttons' ).on( 'click', '.rai-filter-button', function(){

				var filterValue = jQuery(this).attr('data-filter');

				eachWraper.find( '.rai_gallery_filter' ).isotope({ filter: filterValue });

			} );

		});
        
        //jQuery('.rai-filter-button.rai-filter-active').trigger('click');
	
	});*/

})(jQuery);
