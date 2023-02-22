<div class="uig-img-viewer">
<?php
	$uig_gallery_type = !empty(get_post_meta($id, 'uig_gallery_type', true)) ? get_post_meta($id, 'uig_gallery_type', true) : 'image_gallery';
	$gallery_items = !empty(get_post_meta($id,'uig_gallery_items', true)) ? get_post_meta($id,'uig_gallery_items', true) : array();
	
	$filter_wrapper_class = 'uig-image-gallery-wrapper';
	if( $uig_gallery_type == 'filterable_gallery' ){
		$filter_wrapper_class = 'uig-filter-gallery-wrapper';
	}
	?>
	<div class="<?php echo esc_attr($filter_wrapper_class); ?>">
		<?php
		if( $uig_gallery_type == 'filterable_gallery' ){
			include_once 'filter-buttons.php';
		}
		?>
		<ul id="uig_gallery_images">
			<?php 
			$display_image_title = !empty(get_post_meta($id, 'uig_display_image_title', true)) ? get_post_meta($id, 'uig_display_image_title', true) : '';

			foreach( $gallery_items as $gallery_item ) {
				$image_url = $gallery_item['image_url'];
				$image_title = $gallery_item['image_title'];
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
		</ul>
	</div>
</div>