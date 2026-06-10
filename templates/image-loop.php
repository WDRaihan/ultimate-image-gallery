<?php
if($uig_masonry_layout == 'yes'){
	$uig_gallery_item_class = 'uig-masonry-gallery-item';
}else{
	$uig_gallery_item_class = 'uig-grid-gallery-item';
}

$uig_item_meta_class = '';
if($display_image_description == 'yes' && !empty($image_description)){
	$uig_item_meta_class = 'uig-item-meta-with-description';
}
?>
<div class="uig-gallery-item <?php echo esc_attr($uig_gallery_item_class); ?> <?php echo esc_attr($filter_categories); ?>">
	<img src="<?php echo esc_url($image_url) ?>" alt="<?php echo esc_html($image_title); ?>">
	<?php if($display_image_title == 'yes' && !empty($image_title)): ?>
	<div class="uig-item-meta <?php echo esc_attr($uig_item_meta_class); ?>">
		<?php if($display_image_title == 'yes' && !empty($image_title)): ?>
		<h2 class="uig-image-title"><?php echo esc_html($image_title); ?></h2>
		<?php endif; ?>
		
		<?php if($display_image_description == 'yes' && !empty($image_description)): ?>
		<p class="uig-image-description"><?php echo wp_kses( $image_description, array( 'br' => array() ) ); ?></p>

		<?php endif; ?>
	</div>
	<?php endif; ?>
	</div>