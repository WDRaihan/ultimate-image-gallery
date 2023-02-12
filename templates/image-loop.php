<li class="uig-gallery-item <?php echo esc_attr($filter_categories); ?>">
	<img src="<?php echo esc_url($image_url) ?>" alt="<?php echo esc_html($image_title); ?>">
	<?php if($display_image_title == 'yes' && !empty($image_title)): ?>
	<h2 class="uig-image-title"><?php echo esc_html($image_title); ?></h2>
	<?php endif; ?>
</li>