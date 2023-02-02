<?php 

/*
Plugin Name: Ultimate Image Gallery - Image Zoom, Viewer, Lightbox and Filter Gallery
Author: wdraihan
Description:This is a beautiful responsive image gallery. You can see each image with 10000% zoom and it has  more features. After installing it, a Gallery menu will be created on your WordPress dashboard, form which you can easily set the image. You can use it as a portfolio gallery, photo gallery,  photo album, image gallery, widget image gallery etc. (You have to use this shortcode "[uig_image_gallery] or [uig_image_gallery title="your title" description="Your description"][/uig_image_gallery]" in your page to show the gallery).
Version: 1.0
Text Domain: ultimate_image_gallery
Domain Path: /languages
*/
/*
Shortcodes: 
[ultimate_image_gallery]
[ultimate_image_gallery]
*/

class UIG_Ultimate_Image_Gallery {
    
    public function __construct(){
        
        add_action( 'add_meta_boxes', array($this,'uig_register_meta_boxes') );
        add_action( 'save_post', array($this,'uig_save_meta_box' ), 10, 2 );
        
        add_action('init', array($this, 'ultimate_image_gallery_function'));
        add_action('wp_enqueue_scripts', array($this,'ultimate_image_gallery_scripts'));
        add_action('admin_enqueue_scripts', array($this,'admin_scripts'));
        add_shortcode('uig_image_gallery', array($this, 'ultimate_image_gallery_scode'));
        add_shortcode('uig_filter_gallery', array($this, 'uig_filter_gallery_scode'));
        
        add_action( 'init', array($this, 'uig_register_private_taxonomy'), 0 );
        
        // admin column
        add_filter('manage_uig_image_gallery_posts_columns', array($this, 'uig_custom_columns' ), 10);
        add_action('manage_posts_custom_column', array($this, 'uig_custom_columns_shortcode' ), 10, 2);
        
        define('BG_CSS_URI', trailingslashit('assets/css'));
        define('BG_JS_URI', trailingslashit('assets/js'));
    }
    
    
    /**
     * Register meta box(es).
     */
    public function uig_register_meta_boxes() {
        add_meta_box( 'uig_gallery_metabox', __( 'Ultimate Gallery', 'ultimate_image_gallery' ), array($this, 'uig_gallery_metabox_callback' ), 'uig_image_gallery' );
        add_meta_box('uig_shortcode_metabox','Gallery Shortcode', array( $this, 'uig_shortcode_metabox_callback' ),'uig_image_gallery','side','high');
    }
    

    /**
     * Meta box display callback.
     *
     * @param WP_Post $post Current post object.
     */
    function uig_gallery_metabox_callback( $post ) {
   	?>
    
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
    <?php
    }
     
    public function uig_shortcode_metabox_callback(){
        $uig_scode = isset($_GET['post']) ? '[uig_image_gallery id="'.$_GET['post'].'"]' : '';
        ?>
        <input type="text" name="uig_display_shortcode" class="uig_display_shortcode" value="<?php echo esc_attr($uig_scode); ?>" readonly>

        <div id="uig_shortcode_copied_notice">Shortcode Copied!</div>
        
        
        <?php
    }

    /**
     * Save meta box content.
     *
     * @param int $post_id Post ID
     */
    public function uig_save_meta_box( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if ( ! isset( $_POST[ 'uig_meta_box_noncename' ] ) || ! wp_verify_nonce( $_POST['uig_meta_box_noncename'], 'uig_meta_box_nonce' ) )
			return;

		if ( ! current_user_can( 'edit_posts' ) )
			return;
		
		//Gallery type
		if( isset($_POST['uig_gallery_type']) ){
            update_post_meta( $post_id, 'uig_gallery_type', esc_attr( $_POST['uig_gallery_type'] ) );
        }
		
		//Gallery content: image, title, category
		$all_items = array();
		foreach($_POST['uig_gallery_image_url'] as $k=>$item){
			$all_items[] = array(
				'image_url'	=> sanitize_url( $item ),
				'image_title' => sanitize_text_field( $_POST['uig_image_title'][$k] ),
				'filter_category' => esc_attr( $_POST['uig_filter_category'][$k] )
			);
		}
		update_post_meta( $post_id, 'uig_gallery_items', $all_items );
		
		//Display image title
        update_post_meta( $post_id, 'uig_display_image_title', esc_attr( $_POST['uig_display_image_title'] ) );
        
    }
    
    
    public function ultimate_image_gallery_function(){
        load_plugin_textdomain('ultimate_image_gallery', false, dirname(__FILE__).'/language');
		
        register_post_type('uig_image_gallery', array(
            'labels'=>array(
                'name'=>'Ultimate Gallery',
                'all_items'=>'All Galleries',
                'add_new'=>'Add Gallery',
                'add_new_item' => 'Add new Gallery',
                'edit_item'  => 'Edit Gallery',
                'view_items' => 'View Galleries',
                'not_found' => 'No gallery found',
                'not_found_in_trash' => 'No gallery found in trash',
            ),
            'public'=>true,
            'menu_icon'=>'dashicons-format-image',
            'supports'=>array('title')
        ));
        
    }
    
    function uig_register_private_taxonomy() {
        $args = array(
            'label'        => __( 'Filter category', 'ultimate_image_gallery' ),
            'public'       => true,
            'rewrite'      => false,
            'hierarchical' => false
        );

        register_taxonomy( 'uig-filter-category', 'uig_image_gallery', $args );
    }
    
    
    public function uig_custom_columns($columns) {
        
        $columns['uig_gallery_shortcode'] = esc_html__('Shortcode', 'ultimate_image_gallery');
        unset($columns['date']);
        $columns['date'] = __( 'Date' );
        
        return $columns;
    }
    
    function uig_custom_columns_shortcode($column_name, $id){  
        if($column_name === 'uig_gallery_shortcode') { 
            $post_id =	$id;
            $shortcode = 'uig_image_gallery id="' . $post_id . '"';
            echo "<input type='text' readonly value='[".$shortcode."]'>";
        }
    }
    
    public function ultimate_image_gallery_scripts(){
        //CSS style
        wp_enqueue_style('uig-viewercss', plugins_url(BG_CSS_URI.'viewer.css',__FILE__));
        wp_enqueue_style('uig-imageviewercss', plugins_url(BG_CSS_URI.'imageviewer.css',__FILE__));
        
        //JS script
        wp_enqueue_script('uig-isotope.pkgd.min', '//unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js',array('jquery'),null,true);
        
        wp_enqueue_script('uig-viewerjs', plugins_url(BG_JS_URI.'viewer.js',__FILE__),array('jquery'),null,true);
        wp_enqueue_script('uig-imageviewerjs', plugins_url(BG_JS_URI.'imageviewer.js',__FILE__),array('jquery','uig-isotope.pkgd.min'),null,true);
        
        /*wp_enqueue_script('uig-isotope.pkgd', '//unpkg.com/isotope-layout@3/dist/isotope.pkgd.js',array('jquery'),null,true);*/
        
    }
    
    public function admin_scripts(){
        //CSS style
        wp_enqueue_style('uig-admin-styles', plugins_url('assets/admin/css/admin-style.css',__FILE__));
        
        //JS script
        wp_enqueue_script('uig-admin-scripts', plugins_url('assets/admin/js/admin-scripts.js',__FILE__),array('jquery'),null,true);

    }
    
    
    public function ultimate_image_gallery_scode($img_attr, $img_content){
        //Shortcode [uig_image_gallery title="your title" description="Description"][/uig_image_gallery]
        $scode_atts = shortcode_atts(array(
                'id'=>''
            ),$img_attr);
        extract($scode_atts);

        ob_start();
        ?>
        <div class="img-viewer">

            <div>
                <ul id="images">
                    <?php 
                    $gallery_items = !empty(get_post_meta($id,'uig_gallery_items', true)) ? get_post_meta($id,'uig_gallery_items', true) : array(); 
					
					$display_image_title = !empty(get_post_meta($id, 'uig_display_image_title', true)) ? get_post_meta($id, 'uig_display_image_title', true) : '';
		
                    foreach( $gallery_items as $gallery_item ) {
                        $image_url = $gallery_item['image_url'];
                        $image_title = $gallery_item['image_title'];
						
                        if( $image_url ) {
                            ?>
							<li class="uig-gallery-item">
								<img src="<?php echo esc_url($image_url) ?>" alt="<?php echo esc_html($image_title); ?>">
								
								<?php if($display_image_title == 'yes' && !empty($image_title)): ?>
								<h2 class="uig-image-title"><?php echo esc_html($image_title); ?></h2>
								<?php endif; ?>
							</li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
<?php
    return ob_get_clean();
    }
    
    public function uig_filter_gallery_scode($img_attr, $img_content = null){
        //Shortcode [uig_image_gallery title="your title" description="Description"][/uig_image_gallery]
        $scode_atts = shortcode_atts(array(
                'categories'=>'',
                'title' => ''
            ),$img_attr);
            extract($scode_atts);

        ob_start();
        
        $categories = explode(',',$categories);
        
        $args = array(
            'post_type' => 'uig_image_gallery',
            'tax_query' => array(
                array(
                    'taxonomy' => 'uig-filter-category',
                    'field'    => 'term_id',
                    'terms'    => $categories,
                ),
            ),
        );
        $query = new WP_Query( $args );
        
        ?>
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
                   foreach( $categories as $category_id ){
                       $term = get_term_by('id', $category_id, 'uig-filter-category');
                       ?>
                       <button class="uig-filter-button" data-filter="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></button>
                        <?php
                   }
                   ?>
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
<?php
    return ob_get_clean();
    }
}
new UIG_Ultimate_Image_Gallery();

//add_action("wp_ajax_save_repeatable_fields", "save_repeatable_fields");
//
//function save_repeatable_fields() {
//  $post_id = intval($_POST["post_id"]);
//  $fields = $_POST["fields"];
//
//  // Sanitize the field values
//  $fields = array_map(function($field) {
//    return array_map("sanitize_text_field", $field);
//  }, $fields);
//
//  // Save the field values to post meta
//  update_post_meta($post_id, "repeatable_fields", $fields);
//
//  wp_send_json_success();
//}
