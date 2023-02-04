<div class="img-viewer">
<?php
	$uig_gallery_type = !empty(get_post_meta($id, 'uig_gallery_type', true)) ? get_post_meta($id, 'uig_gallery_type', true) : 'image_gallery';
	
	$wrapper_class = '';
	if( $uig_gallery_type == 'filter_gallery' ){
		$wrapper_class = 'uig-filter-gallery-wrapper';
	}
	?>
	<div class="<?php echo esc_attr($filter_wrapper_class); ?>">
		<?php
		if( $uig_gallery_type == 'filter_gallery' ){
			include_once 'templates/filter-buttons.php';
		}
		?>
		<ul id="images">
			<?php 
			$gallery_items = !empty(get_post_meta($id,'uig_gallery_items', true)) ? get_post_meta($id,'uig_gallery_items', true) : array(); 

			$display_image_title = !empty(get_post_meta($id, 'uig_display_image_title', true)) ? get_post_meta($id, 'uig_display_image_title', true) : '';

			foreach( $gallery_items as $gallery_item ) {
				$image_url = $gallery_item['image_url'];
				$image_title = $gallery_item['image_title'];
				
				if( !empty($gallery_item['filter_category']) ){
					$category_id = $gallery_item['filter_category'];
					//$category = get_term_by( 'term_id', $category_id, 'uig-filter-category' );
					//$category = $category->slug;
					
					print_r($category_id);
				}
				if( $uig_gallery_type != 'filter_gallery' ){
					$category = '';
				}
				
				if( $image_url ) {
					include 'image-loop.php';
				}
			}
			?>
		</ul>
	</div>
</div>