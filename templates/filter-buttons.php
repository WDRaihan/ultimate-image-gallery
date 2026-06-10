<?php
$filter_button_bg_color = !empty(get_post_meta($id, 'uig_filter_button_bg_color', true)) ? get_post_meta($id, 'uig_filter_button_bg_color', true) : '#f5f5f5';
$filter_button_text_color = !empty(get_post_meta($id, 'uig_filter_button_text_color', true)) ? get_post_meta($id, 'uig_filter_button_text_color', true) : '#222';

$filter_button_bg_active_color = !empty(get_post_meta($id, 'uig_filter_button_active_bg_color', true)) ? get_post_meta($id, 'uig_filter_button_active_bg_color', true) : '#16a085';
$filter_button_text_active_color = !empty(get_post_meta($id, 'uig_filter_button_active_text_color', true)) ? get_post_meta($id, 'uig_filter_button_active_text_color', true) : '#fff';
?>
<style>
	.uig-img-viewer-<?php echo esc_attr($id); ?> .uig-filter-buttons button.uig-filter-button {
		background-color: <?php echo esc_attr($filter_button_bg_color); ?>;
		border: 1px solid <?php echo esc_attr($filter_button_bg_color); ?>;
		color: <?php echo esc_attr($filter_button_text_color); ?>;
	}
	.uig-img-viewer-<?php echo esc_attr($id); ?> .uig-filter-buttons button.uig-filter-button.active,
	.uig-img-viewer-<?php echo esc_attr($id); ?> .uig-filter-buttons button.uig-filter-button:hover {
		background-color: <?php echo esc_attr($filter_button_bg_active_color); ?>;
		color: <?php echo esc_attr($filter_button_text_active_color); ?>;
		border: 1px solid <?php echo esc_attr($filter_button_bg_active_color); ?>;
	}
	
</style>
<div class="uig-filter-buttons" data-isotope-key="filter">
	<?php
	echo '<button class="uig-filter-button active" data-filter="all">'.esc_html__('All','ultimate_image_gallery').'</button>';
	 
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
			echo '<button class="uig-filter-button" data-rel="'. esc_attr($term->slug) .'" data-filter=".'. esc_attr($term->slug) .'">'. esc_html($term->name) .'</button>';
		}
	}
	?>
</div>