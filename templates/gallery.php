<div class="uig-img-viewer uig-img-viewer-<?php echo esc_attr($id); ?>" gallery_id="<?php echo esc_attr($id); ?>">
<?php
	$uig_gallery_type = !empty(get_post_meta($id, 'uig_gallery_type', true)) ? get_post_meta($id, 'uig_gallery_type', true) : 'image_gallery';
	$gallery_items = !empty(get_post_meta($id,'uig_gallery_items', true)) ? get_post_meta($id,'uig_gallery_items', true) : array();
	$uig_masonry_layout = !empty(get_post_meta($id, 'uig_masonry_layout', true)) ? get_post_meta($id, 'uig_masonry_layout', true) : '';
	$uig_gallery_column = !empty(get_post_meta($id, 'uig_gallery_column', true)) ? get_post_meta($id, 'uig_gallery_column', true) : 'three-column';
	$uig_gallery_item_space = !empty(get_post_meta($id, 'uig_gallery_item_space', true)) ? get_post_meta($id, 'uig_gallery_item_space', true) : 'five-px';
	$uig_border_radius = !empty(get_post_meta($id, 'uig_border_radius', true)) ? get_post_meta($id, 'uig_border_radius', true) : '0';
	$uig_enable_image_zoom = metadata_exists('post', $id, 'uig_enable_image_zoom') ? get_post_meta($id, 'uig_enable_image_zoom', true) : 'yes';
	$uig_zoom_gallery_class = $uig_enable_image_zoom == 'yes' ? 'uig_zoom_gallery' : '';
	
	?>
	<style>
		.uig-img-viewer.uig-img-viewer-<?php echo esc_attr($id); ?> .uig_gallery_images .uig-gallery-item {
			border-radius: <?php echo esc_attr($uig_border_radius); ?>px;
		}
	</style>
	<?php

	$filter_wrapper_class = 'uig-image-gallery-wrapper';
	if( $uig_gallery_type == 'filterable_gallery' ){
		$filter_wrapper_class = 'uig-filter-gallery-wrapper';
	}
	if($uig_masonry_layout == 'yes'){
		$uig_gallery_layout = 'uig_masonry_layout';
	}else{
		$uig_gallery_layout = 'uig_grid_layout';
	}
	?>
	<div class="<?php echo esc_attr($filter_wrapper_class); ?>">
		<?php
		if( $uig_gallery_type == 'filterable_gallery' ){
			include 'filter-buttons.php';
		}
		?>
		<div id="uig_gallery_images_<?php echo esc_attr($id); ?>" class="uig_gallery_images <?php echo esc_attr($uig_zoom_gallery_class); ?> <?php echo esc_attr($uig_gallery_layout); ?> <?php echo esc_attr($uig_gallery_column); ?> <?php echo esc_attr($uig_gallery_item_space); ?>">
			<?php if($uig_masonry_layout == 'yes') : ?>
			<?php endif; ?>
			<?php 
			$display_image_title = !empty(get_post_meta($id, 'uig_display_image_title', true)) ? get_post_meta($id, 'uig_display_image_title', true) : '';
			$display_image_description = !empty(get_post_meta($id, 'uig_display_image_description', true)) ? get_post_meta($id, 'uig_display_image_description', true) : '';

			foreach( $gallery_items as $gallery_item ) {
				$image_url = $gallery_item['image_url'];
				$image_title = $gallery_item['image_title'];
				$image_description = $gallery_item['image_description'];
				$filter_categories = '';
				
				if( $uig_gallery_type == 'filterable_gallery' ){
					$category_ids = !empty($gallery_item['filter_category']) ? $gallery_item['filter_category'] : array();
					
					if( !empty($category_ids) ){
						
						$slugs = array();
						foreach($category_ids as $category_id) {
							$term = get_term_by('id', $category_id, 'uig-filter-category');
							if($term != null){
								$slugs[] = $term->slug;
							}
						}

						$filter_categories = implode(' ', $slugs);
						
						if( $image_url ) {
							include 'image-loop.php';
						}
					}
					
				}else {
					if( $image_url ) {
						include 'image-loop.php';
					}
				}
			}
			?>
		</div>
	</div>
</div>
