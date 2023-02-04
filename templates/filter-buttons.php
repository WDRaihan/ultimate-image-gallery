<div class="uig-filter-buttons" data-isotope-key="filter">
	<button class="uig-filter-button uig-filter-active" data-filter="*"><?php echo esc_html__('All','ultimate_image_gallery'); ?></button>
	<?php 
	foreach( $categories as $category_id ) {
		$term = get_term_by('id', $category_id, 'uig-filter-category');

		echo '<button class="uig-filter-button" data-filter="'. esc_attr($term->slug) .'">'. esc_html($term->name) .'</button>';
	}
	?>
</div>