<!--Filter type-->
<h4 class="uig-gallery-settings-heading"><?php echo esc_html__('Gallery type','ultimate_image_gallery'); ?></h4>
<div class="uig-filter-type-tabs">
	<?php
		$uig_gallery_type = !empty(get_post_meta($post->ID, 'uig_gallery_type', true)) ? get_post_meta($post->ID, 'uig_gallery_type', true) : 'image_gallery';
		?>
	<input class="uig_gallery_type" type="radio" name="uig_gallery_type" id="uig_type_image_gallery" value="image_gallery" <?php checked('image_gallery',$uig_gallery_type,true); ?>>
	<label for="uig_type_image_gallery" class="uig-filter-type-tab"><?php echo esc_html__('Image gallery','ultimate_image_gallery'); ?></label>
	<input class="uig_gallery_type" type="radio" name="uig_gallery_type" id="uig_type_filterable_gallery" value="filterable_gallery" <?php checked('filterable_gallery',$uig_gallery_type,true); ?>>
	<label for="uig_type_filterable_gallery" class="uig-filter-type-tab"><?php echo esc_html__('Filterable image gallery','ultimate_image_gallery'); ?></label>
</div>
<hr>
<?php
	$hide_show_category_field = '';
	if($uig_gallery_type == 'image_gallery'){
		$hide_show_category_field = 'hidden-if-image-gallery';
	}
	?>
<h4 class="uig-gallery-settings-heading left-align"><?php echo esc_html__('Add gallery images','ultimate_image_gallery'); ?></h4>
<div class="uig-repeater-container">
	<div class="uig-field-item-clone" style="display:none">
		<div id="uig_field_item_clone" class="uig-field-item">
			<div class="uig-repeater-action-buttons">
				<span class="dashicons dashicons-move"></span>
				<img class="uig-gallery-perview-image" src="">
				<div><span class="toggle-button dashicons dashicons-arrow-up-alt2"></span><a href="#" class="uig-remove-field"><?php echo esc_html__('Remove','ultimate_image_gallery'); ?></a></div>
			</div>
			<div class="uig-gallery-fields-wrapper">
				<ul>
					<li>
						<label class="uig-gallery-form-control-lebel"><?php echo esc_html__('Upload Image or Enter URL','ultimate_image_gallery'); ?></label>
						<div class="uig-image-field-wrappper">
							<input class="uig-gallery-form-control uig-image-url" type="text" name="xxx_uig_gallery_image_url[]" value="" placeholder="<?php echo esc_html__('Enter image URL','ultimate_image_gallery'); ?>"><a class="uig-gallery-image-upload button button-primary button-large uig-image-upload" href="#"><?php echo esc_html__('Upload','ultimate_image_gallery'); ?></a>
						</div>
					</li>
					<li>
						<label class="uig-gallery-form-control-lebel"><?php echo esc_html__('Image Title','ultimate_image_gallery'); ?></label>
						<input class="uig-gallery-form-control" type="text" name="xxx_uig_image_title[]" value="" placeholder="<?php echo esc_html__('Enter image title','ultimate_image_gallery'); ?>">
					</li>
					<li class="uig_filter_category_field <?php echo esc_attr($hide_show_category_field); ?>">
						<label class="uig-gallery-form-control-lebel">Filter Category</label>
						<?php
                            $args = array(
                                'taxonomy' => 'uig-filter-category',
                                'name' => 'xxx_uig_filter_category[]',
                                'class' => 'uig-filter-category uig-gallery-form-control',
                                'show_option_none' => esc_html__('Select a category','ultimate_image_gallery'),
                                'option_none_value' => '',
                                'hide_empty' => false
                            );
                            wp_dropdown_categories( $args );
                            ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div id="uig-repeatable-fields">
		<?php 
		$uig_gallery_items = !empty(get_post_meta($post->ID,'uig_gallery_items', true)) ? get_post_meta($post->ID,'uig_gallery_items', true) : array(); 
		
		foreach( $uig_gallery_items as $uig_gallery_item ):
		?>
		<div class="uig-field-item">
			<div class="uig-repeater-action-buttons">
				<span class="dashicons dashicons-move"></span>
				<img class="uig-gallery-perview-image" src="<?php echo esc_url($uig_gallery_item['image_url']); ?>">
				<div><span class="toggle-button dashicons dashicons-arrow-up-alt2"></span><a href="#" class="uig-remove-field"><?php echo esc_html__('Remove','ultimate_image_gallery'); ?></a></div>
			</div>
			<div class="uig-gallery-fields-wrapper">
				<ul>
					<li>
						<label class="uig-gallery-form-control-lebel"><?php echo esc_html__('Upload Image or Enter URL','ultimate_image_gallery'); ?></label>
						<div class="uig-image-field-wrappper">
							<input class="uig-gallery-form-control uig-image-url" type="text" name="uig_gallery_image_url[]" value="<?php echo esc_url($uig_gallery_item['image_url']); ?>" placeholder="<?php echo esc_html__('Enter image URL','ultimate_image_gallery'); ?>"><a class="uig-gallery-image-upload button button-primary button-large uig-image-upload" href="#"><?php echo esc_html__('Upload','ultimate_image_gallery'); ?></a>
						</div>
					</li>
					<li>
						<label class="uig-gallery-form-control-lebel"><?php echo esc_html__('Image Title','ultimate_image_gallery'); ?></label>
						<input class="uig-gallery-form-control" type="text" name="uig_image_title[]" value="<?php echo esc_html($uig_gallery_item['image_title']); ?>" placeholder="<?php echo esc_html__('Enter image title','ultimate_image_gallery'); ?>">
					</li>
					<li class="uig_filter_category_field  <?php echo esc_attr($hide_show_category_field); ?>">
						<label class="uig-gallery-form-control-lebel"><?php echo esc_html__('Filter Category','ultimate_image_gallery'); ?></label>
						<?php
                            $args = array(
                                'taxonomy' => 'uig-filter-category',
                                'name' => 'uig_filter_category[]',
                                'class' => 'uig-filter-category uig-gallery-form-control',
                                'show_option_none' => esc_html__('Select a category','ultimate_image_gallery'),
                                'option_none_value' => '',
                                'hide_empty' => false,
								'selected' => esc_attr($uig_gallery_item['filter_category'])
                            );
                            wp_dropdown_categories( $args );
                            ?>
					</li>
				</ul>
			</div>
		</div>
		<?php endforeach; //end item?>
	</div>
	<div class="uig-add-more-wrapper"><a href="#" id="uig-add-field"><span class="dashicons dashicons-plus-alt2"></span> <?php echo esc_html__('Add image','ultimate_image_gallery'); ?></a></div>
</div>
<hr>
<p>
	<?php
		$display_image_title = !empty(get_post_meta($post->ID, 'uig_display_image_title', true)) ? get_post_meta($post->ID, 'uig_display_image_title', true) : '';
		?>
	<label for="display-title"><input id="display-title" name="uig_display_image_title" type="checkbox" value="yes" <?php checked('yes',$display_image_title,true); ?>> Display image title</label>
</p>
<?php wp_nonce_field( 'uig_meta_box_nonce', 'uig_meta_box_noncename' ); ?>