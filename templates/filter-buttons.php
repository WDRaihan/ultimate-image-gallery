<div class="uig-filter-buttons" data-isotope-key="filter">
	<?php
	echo '<button class="uig-filter-button uig-filter-active" data-filter="*">'.esc_html__('All','ultimate_image_gallery').'</button>';
	 
	$filter_category_ids = array();
	foreach( $gallery_items as $gallery_item ) {
				
		$filter_categories = '';

		$category_ids = !empty($gallery_item['filter_category']) ? $gallery_item['filter_category'] : array();

		if( !empty($category_ids) ){

			foreach($category_ids as $category_id) {
				$filter_category_ids[] = $category_id;
			}
		}
	}
	
	$filter_category_ids = array_unique($filter_category_ids);
	
	foreach( $filter_category_ids as $filter_category_id ) {
		$term = get_term_by('id', $filter_category_id, 'uig-filter-category');
		if($term != null){
			echo '<button class="uig-filter-button" data-filter="'. esc_attr($term->slug) .'">'. esc_html($term->name) .'</button>';
		}
	}
	?>
</div>