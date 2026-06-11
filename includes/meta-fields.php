<!-- Tab: content and style -->
<div class="uig-content-and-style-tabs">
	<ul>
		<li class="uig-tab-content uig-tab-active"><?php echo esc_html__('Contents','ultimate_image_gallery'); ?></li>
		<li class="uig-tab-style"><?php echo esc_html__('Styles','ultimate_image_gallery'); ?></li>
		<li class="uig-tab-shortcode"><?php echo esc_html__('Shortcode','ultimate_image_gallery'); ?></li>
	</ul>
</div>

<!-- Start content tab-->
<div class="uig-tab-data-contents">
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
						<li>
							<label class="uig-gallery-form-control-lebel"><?php echo esc_html__('Image Description','ultimate_image_gallery'); ?></label>
							<input class="uig-gallery-form-control" type="text" name="xxx_uig_image_description[]" value="" placeholder="<?php echo esc_html__('Enter image description','ultimate_image_gallery'); ?>">
						</li>
						<li class="uig-readmore-item-field">
							<label class="uig-gallery-form-control-lebel"><?php echo esc_html__('Read More Mode','ultimate_image_gallery'); ?></label>
							<div class="uig-readmore-mode-wrap" style="margin-bottom:6px;">
								<label style="margin-right:14px;"><input type="radio" class="uig-item-readmore-radio" name="xxx_uig_item_readmore_mode[]" value="popup" checked> <?php echo esc_html__('Popup / Modal','ultimate_image_gallery'); ?></label>
								<label><input type="radio" class="uig-item-readmore-radio" name="xxx_uig_item_readmore_mode[]" value="page"> <?php echo esc_html__('Link to Page','ultimate_image_gallery'); ?></label>
							</div>
							<div class="uig-item-readmore-url-wrap" style="display:none;">
								<input type="url" class="uig-gallery-form-control" name="xxx_uig_item_readmore_page_url[]" value="" placeholder="<?php echo esc_html__('https://example.com/page','ultimate_image_gallery'); ?>">
							</div>
						</li>
						<li class="uig_filter_category_field <?php echo esc_attr($hide_show_category_field); ?>">
							<label class="uig-gallery-form-control-lebel"><?php echo esc_html__('Filter Category','ultimate_image_gallery'); ?></label>
							<select name="xxx_uig_filter_category[]" class="uig-filter-category uig-gallery-form-control">
								<?php 
								$taxonomy = 'uig-filter-category';
								$uig_terms = get_terms( array(
									'taxonomy' => $taxonomy,
									'hide_empty' => false,
								) );
								
								$default_term_id = get_option($taxonomy . "_default");
								
								// Reorder the terms array to make the default term appear first
								usort( $uig_terms, function( $a, $b ) use ( $default_term_id ) {
									if ( $a->term_id == $default_term_id ) {
										return -1;
									}
									if ( $b->term_id == $default_term_id ) {
										return 1;
									}
									return 0;
								});
								
								foreach ( $uig_terms as $uig_term ) {
									echo '<option value="' . esc_attr($uig_term->term_id) . '">' . esc_html($uig_term->name) . '</option>';
								}
								?>
							</select>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div id="uig-repeatable-fields">
			<?php 
			$uig_gallery_items = !empty(get_post_meta($post->ID,'uig_gallery_items', true)) ? get_post_meta($post->ID,'uig_gallery_items', true) : array(); 
			$xx = 0;
			foreach( $uig_gallery_items as $uig_gallery_item ):
				if (array_key_exists("image_url",$uig_gallery_item)){
					$image_url = $uig_gallery_item['image_url'];
				}else{
					$image_url = '';
				}
				if (array_key_exists("image_title",$uig_gallery_item)){
					$image_title = $uig_gallery_item['image_title'];
				}else{
					$image_title = '';
				}
				if (array_key_exists("image_description",$uig_gallery_item)){
					$image_description = $uig_gallery_item['image_description'];
				}else{
					$image_description = '';
				}
				$_uig_item_rm_mode     = isset( $uig_gallery_item['read_more_mode'] ) ? $uig_gallery_item['read_more_mode'] : 'popup';
				$_uig_item_rm_page_url = isset( $uig_gallery_item['read_more_page_url'] ) ? $uig_gallery_item['read_more_page_url'] : '';
			?>
			<div class="uig-field-item">
				<div class="uig-repeater-action-buttons">
					<span class="dashicons dashicons-move"></span>
					<img class="uig-gallery-perview-image" src="<?php echo esc_url($image_url); ?>">
					<div><span class="toggle-button dashicons dashicons-arrow-up-alt2"></span><a href="#" class="uig-remove-field"><?php echo esc_html__('Remove','ultimate_image_gallery'); ?></a></div>
				</div>
				<div class="uig-gallery-fields-wrapper">
					<ul>
						<li>
							<label class="uig-gallery-form-control-lebel"><?php echo esc_html__('Upload Image or Enter URL','ultimate_image_gallery'); ?></label>
							<div class="uig-image-field-wrappper">
								<input class="uig-gallery-form-control uig-image-url" type="text" name="uig_gallery_image_url[]" value="<?php echo esc_url($image_url); ?>" placeholder="<?php echo esc_html__('Enter image URL','ultimate_image_gallery'); ?>"><a class="uig-gallery-image-upload button button-primary button-large uig-image-upload" href="#"><?php echo esc_html__('Upload','ultimate_image_gallery'); ?></a>
							</div>
						</li>
						<li>
							<label class="uig-gallery-form-control-lebel"><?php echo esc_html__('Image Title','ultimate_image_gallery'); ?></label>
							<input class="uig-gallery-form-control" type="text" name="uig_image_title[]" value="<?php echo esc_html($image_title); ?>" placeholder="<?php echo esc_html__('Enter image title','ultimate_image_gallery'); ?>">
						</li>
						<li class="uig_disabled_field-">
							<label class="uig-gallery-form-control-lebel"><?php echo esc_html__('Image Description','ultimate_image_gallery'); ?></label>
							<input class="uig-gallery-form-control" type="text" name="uig_image_description[]" value="<?php echo wp_kses( $image_description, array( 'br' => array() ) ); ?>" placeholder="<?php echo esc_html__('Enter image description','ultimate_image_gallery'); ?>">
						</li>
						<li class="uig-readmore-item-field">
							<label class="uig-gallery-form-control-lebel"><?php echo esc_html__('Read More Mode','ultimate_image_gallery'); ?></label>
							<div class="uig-readmore-mode-wrap" style="margin-bottom:6px;">
								<label style="margin-right:14px;"><input type="radio" class="uig-item-readmore-radio" name="uig_item_readmore_mode[<?php echo esc_attr($xx); ?>]" value="popup" <?php checked( 'popup', $_uig_item_rm_mode ); ?>> <?php echo esc_html__('Popup / Modal','ultimate_image_gallery'); ?></label>
								<label><input type="radio" class="uig-item-readmore-radio" name="uig_item_readmore_mode[<?php echo esc_attr($xx); ?>]" value="page" <?php checked( 'page', $_uig_item_rm_mode ); ?>> <?php echo esc_html__('Link to Page','ultimate_image_gallery'); ?></label>
							</div>
							<div class="uig-item-readmore-url-wrap" style="<?php echo ( $_uig_item_rm_mode !== 'page' ) ? 'display:none;' : ''; ?>">
								<input type="url" class="uig-gallery-form-control" name="uig_item_readmore_page_url[<?php echo esc_attr($xx); ?>]" value="<?php echo esc_url( $_uig_item_rm_page_url ); ?>" placeholder="<?php echo esc_html__('https://example.com/page','ultimate_image_gallery'); ?>">
							</div>
						</li>
						<li class="uig_filter_category_field  <?php echo esc_attr($hide_show_category_field); ?>">
							<label class="uig-gallery-form-control-lebel"><?php echo esc_html__('Filter Category','ultimate_image_gallery'); ?></label>
							<?php
							$filter_category = is_array($uig_gallery_item['filter_category']) ? $uig_gallery_item['filter_category'] : array();
							?>
							<select name="uig_filter_category[<?php echo esc_attr($xx); ?>][]" class="uig-filter-category uig-gallery-form-control">
								<?php
								$taxonomy = 'uig-filter-category';
								$uig_terms = get_terms( array(
									'taxonomy' => $taxonomy,
									'hide_empty' => false,
								) );
								
								$default_term_id = get_option($taxonomy . "_default");
								
								// Reorder the terms array to make the default term appear first
								usort( $uig_terms, function( $a, $b ) use ( $default_term_id ) {
									if ( $a->term_id == $default_term_id ) {
										return -1;
									}
									if ( $b->term_id == $default_term_id ) {
										return 1;
									}
									return 0;
								});
								
								foreach ( $uig_terms as $uig_term ) {
									$selected = '';
									if(in_array($uig_term->term_id, $filter_category)){
										$selected = 'selected';
									}
									echo '<option value="' . esc_attr($uig_term->term_id) . '" '.esc_attr($selected).'>' . esc_html($uig_term->name) . '</option>';
								}
								?>
							</select>
						</li>
					</ul>
				</div>
			</div>
			<?php $xx++; endforeach; //end item?>
		</div>
		<div class="uig-add-more-wrapper"><a href="#" id="uig-add-field"><span class="dashicons dashicons-plus-alt2"></span> <?php echo esc_html__('Add image','ultimate_image_gallery'); ?></a></div>
	</div>
	<hr>
	<p>
		<?php
			$display_image_title = !empty(get_post_meta($post->ID, 'uig_display_image_title', true)) ? get_post_meta($post->ID, 'uig_display_image_title', true) : '';
			?>
		<label for="display-title"><input id="display-title" name="uig_display_image_title" type="checkbox" value="yes" <?php checked('yes',$display_image_title,true); ?>> <strong>Display Image Info</strong></label>
	</p>
	<hr>
	<p>
		<?php
			$uig_display_image_description = !empty(get_post_meta($post->ID, 'uig_display_image_description', true)) ? get_post_meta($post->ID, 'uig_display_image_description', true) : '';
			?>
		<label for="display-description"><input id="display-description" name="uig_display_image_description" type="checkbox" value="yes" <?php checked('yes',$uig_display_image_description,true); ?>> <strong>Display Image Description</strong></label>
	</p>
	<hr>
	<p>
		<?php
			$uig_enable_image_zoom = metadata_exists('post', $post->ID, 'uig_enable_image_zoom') ? get_post_meta($post->ID, 'uig_enable_image_zoom', true) : 'yes';
			?>
		<label for="enable-image-zoom"><input id="enable-image-zoom" name="uig_enable_image_zoom" type="checkbox" value="yes" <?php checked('yes',$uig_enable_image_zoom,true); ?>> <strong>Enable Image Zoom</strong></label>
	</p>
	<hr>
	<p>
		<?php
			$uig_masonry_layout = !empty(get_post_meta($post->ID, 'uig_masonry_layout', true)) ? get_post_meta($post->ID, 'uig_masonry_layout', true) : '';
			?>
		<label for="masonry_layout"><input id="masonry_layout" name="uig_masonry_layout" type="checkbox" value="yes" <?php checked('yes',$uig_masonry_layout,true); ?>> <strong>Enable Masonry Layout</strong></label>
	</p>
</div>
<!-- End content tab -->

<!-- Start style tab -->
<div class="uig-tab-data-styles">
	<h4 class="uig-gallery-settings-heading left-align"><?php echo esc_html__('Style Settings','ultimate_image_gallery'); ?></h4>
	<table class="uig-style-meta-table">
		<tbody>
			<?php do_action( 'uig_style_settings_fields', $post ); ?>
			<tr>
				<td>
					<?php
					$uig_gallery_column = !empty(get_post_meta($post->ID, 'uig_gallery_column', true)) ? get_post_meta($post->ID, 'uig_gallery_column', true) : 'three-column';
					?>
					<label for="gallery_column"><strong>Item Columns</strong></label>
				</td>
				<td>
					<select id="gallery_column" class="uig-form-field" name="uig_gallery_column">
						<option value="two-column" <?php selected('two-column', $uig_gallery_column, true); ?>>2 Columns</option>
						<option value="three-column" <?php selected('three-column', $uig_gallery_column, true); ?>>3 Columns</option>
						<option value="four-column" <?php selected('four-column', $uig_gallery_column, true); ?>>4 Columns</option>
					</select> 
				</td>
			</tr>
			<tr>
				<td>
					<?php
					$uig_gallery_item_space = !empty(get_post_meta($post->ID, 'uig_gallery_item_space', true)) ? get_post_meta($post->ID, 'uig_gallery_item_space', true) : 'five-px';
					?>
					<label for="gallery_item_space"><strong>Item Space</strong></label>
				</td>
				<td>
					<select id="gallery_item_space" class="uig-form-field" name="uig_gallery_item_space">
						<option value="five-px" <?php selected('five-px', $uig_gallery_item_space, true); ?>>5 PX</option>
						<option value="ten-px" <?php selected('ten-px', $uig_gallery_item_space, true); ?>>10 PX</option>
						<option value="fifteen-px" <?php selected('fifteen-px', $uig_gallery_item_space, true); ?>>15 PX</option>
						<option value="twenty-px" <?php selected('twenty-px', $uig_gallery_item_space, true); ?>>20 PX</option>
					</select>  
				</td>
			</tr>
			<tr>
				<td>
					<?php
					$uig_border_radius = !empty(get_post_meta($post->ID, 'uig_border_radius', true)) ? get_post_meta($post->ID, 'uig_border_radius', true) : '';
					?>
					<label for="border_radius"><strong>Item Border Radius</strong></label>
				</td>
				<td>
					<input id="border_radius" class="uig-form-field" name="uig_border_radius" type="number" min="0" max="120" value="<?php echo esc_attr($uig_border_radius); ?>" placeholder="0">PX
				</td>
			</tr>
			<tr>
				<?php
				$uig_filter_button_bg_color = !empty(get_post_meta($post->ID, 'uig_filter_button_bg_color', true)) ? get_post_meta($post->ID, 'uig_filter_button_bg_color', true) : '#f5f5f5';
				?>
				<td>
					<label><strong>Filter Button Color</strong></label>
				</td>
				<td>
					<label for="uig_filter_button_bg_color"><strong>Background</strong></label><br>
					<input type="color" name="uig_filter_button_bg_color" id="uig_filter_button_bg_color" class="uig-form-field" value="<?php echo esc_html($uig_filter_button_bg_color); ?>">
				</td>
			</tr>
			<tr>
				<?php
				$uig_filter_button_text_color = !empty(get_post_meta($post->ID, 'uig_filter_button_text_color', true)) ? get_post_meta($post->ID, 'uig_filter_button_text_color', true) : '#222';
				?>
				<td></td>
				<td>
					<label for="uig_filter_button_text_color"><strong>Text</strong></label><br>
					<input type="color" name="uig_filter_button_text_color" id="uig_filter_button_text_color" class="uig-form-field" value="<?php echo esc_html($uig_filter_button_text_color); ?>">
				</td>
			</tr>
			<tr>
				<?php
				$uig_filter_button_active_bg_color = !empty(get_post_meta($post->ID, 'uig_filter_button_active_bg_color', true)) ? get_post_meta($post->ID, 'uig_filter_button_active_bg_color', true) : '#16a085';
				?>
				<td></td>
				<td>
					<label for="uig_filter_button_active_bg_color"><strong>Hover and Active Background</strong></label><br>
					<input type="color" name="uig_filter_button_active_bg_color" id="uig_filter_button_active_bg_color" class="uig-form-field" value="<?php echo esc_html($uig_filter_button_active_bg_color); ?>">
				</td>
			</tr>
			<tr>
				<?php
				$uig_filter_button_active_text_color = !empty(get_post_meta($post->ID, 'uig_filter_button_active_text_color', true)) ? get_post_meta($post->ID, 'uig_filter_button_active_text_color', true) : '#fff';
				?>
				<td></td>
				<td>
					<label for="uig_filter_button_active_text_color"><strong>Hover and Active Text</strong></label><br>
					<input type="color" name="uig_filter_button_active_text_color" id="uig_filter_button_active_text_color" class="uig-form-field" value="<?php echo esc_html($uig_filter_button_active_text_color); ?>">
				</td>
			</tr>
		</tbody>
	</table>
</div>

<!-- Start shortcode tab -->
<div class="uig-tab-data-shortcode">
	<?php
	$uig_scode = isset($_GET['post']) ? '[uig_gallery id="'.$_GET['post'].'"]' : '';
	if(!empty($uig_scode)) {
	?>
	<input type="text" name="uig_display_shortcode" class="uig_display_shortcode" value="<?php echo esc_attr($uig_scode); ?>" readonly>

	<div id="uig_shortcode_copied_notice"><?php echo esc_html__('Shortcode Copied!', 'ultimate_image_gallery'); ?></div>
	
	<ul>
		<li>To display the gallery on your website, just copy and paste <code style="color:#16a085"><?php echo esc_attr($uig_scode); ?></code> into any post, page, or custom post type content.</li>
		<li>If you want to include the gallery directly in your theme files, use <code style="color:#16a085">&lt;?php echo do_shortcode('<?php echo esc_attr($uig_scode); ?>'); ?&gt;</code>.</li>
	</ul>
	<?php 
	}else{
		echo '<p>Please create and publish the gallery to view the shortcode.</p>';
	} 
	?>
</div>
<!-- End style tab -->
<?php wp_nonce_field( 'uig_meta_box_nonce', 'uig_meta_box_noncename' ); ?>
