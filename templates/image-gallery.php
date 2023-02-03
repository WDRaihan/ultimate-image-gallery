<div class="img-viewer">

	<div>
		<ul id="images">
			<?php 
			$gallery_items = !empty(get_post_meta($id,'uig_gallery_items', true)) ? get_post_meta($id,'uig_gallery_items', true) : array(); 

			$display_image_title = !empty(get_post_meta($id, 'uig_display_image_title', true)) ? get_post_meta($id, 'uig_display_image_title', true) : '';

			foreach( $gallery_items as $gallery_item ) {
				$image_url = $gallery_item['image_url'];
				$image_title = $gallery_item['image_title'];

				if( $image_url ) {
					include 'image-loop.php';
				}
			}
			?>
		</ul>
	</div>
</div>