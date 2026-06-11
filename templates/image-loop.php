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

// Build the default description output so pro-features can filter it.
// $id is the gallery post ID (set in gallery.php before this file is included).
$_uig_desc_html = '';
if ( $display_image_description == 'yes' && ! empty( $image_description ) ) {
	$_uig_desc_default = '<p class="uig-image-description">' . wp_kses( $image_description, array( 'br' => array() ) ) . '</p>';
	$_uig_desc_html = apply_filters( 'uig_render_image_description', $_uig_desc_default, $image_description, isset($id) ? $id : 0, isset($gallery_item) ? $gallery_item : array() );
}
?>
<div class="uig-gallery-item <?php echo esc_attr($uig_gallery_item_class); ?> <?php echo esc_attr($filter_categories); ?>">
	<img src="<?php echo esc_url($image_url) ?>" alt="<?php echo esc_html($image_title); ?>">
	<?php if ( ( $display_image_title == 'yes' && ! empty( $image_title ) ) || ! empty( $_uig_desc_html ) ) : ?>
	<div class="uig-item-meta <?php echo esc_attr($uig_item_meta_class); ?>">
		<?php if($display_image_title == 'yes' && !empty($image_title)): ?>
		<h2 class="uig-image-title"><?php echo esc_html($image_title); ?></h2>
		<?php endif; ?>
		
		<?php if ( ! empty( $_uig_desc_html ) ) : ?>
		<?php echo $_uig_desc_html; ?>
		<?php endif; ?>
	</div>
	<?php endif; ?>
	</div>
