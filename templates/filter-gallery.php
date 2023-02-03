<div class="img-viewer">

	<?php if( !empty($title) ) : ?>
	<h2>
		<?php echo esc_html($title); ?>
	</h2>
	<?php endif; ?>

	<div class="uig-filter-gallery-wraper">

		<div class="uig-filter-buttons" data-isotope-key="filter">
			<button class="uig-filter-button uig-filter-active" data-filter="*">show all</button>

			<?php 
		   	foreach( $categories as $category_id ) :
			   $term = get_term_by('id', $category_id, 'uig-filter-category');
			   ?>
				<button class="uig-filter-button" data-filter="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></button>
			<?php endforeach; ?>
		</div>

		<ul id="images" class="uig_gallery_filter">
			<?php 
                    
			while( $query->have_posts() ){
				$query->the_post();
				$id = get_the_id();

				$image_ids = ( $image_ids = get_post_meta( $id, 'uig_gallery_image_ids', true ) ) ? explode(',',$image_ids) : array();

				$term_obj_list = get_the_terms( $id, 'uig-filter-category' );
				$terms_string = join(' ', wp_list_pluck($term_obj_list, 'slug'));

				foreach( $image_ids as $i => $image_id ) {
					$url = wp_get_attachment_image_url( $image_id, 'full' );
					if( $url ) {
						?>
						<li class="uig-gallery-item <?php echo esc_attr($terms_string); ?>">
							<img src="<?php echo esc_url($url) ?>" alt="">
							<h2 class="uig-image-title">Image title</h2>
						</li>
						<?php
					}
				}
			}
			?>
		</ul>
	</div>
</div>