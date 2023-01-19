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
        add_meta_box( 'zoom-gallery-images', __( 'Zoom Gallery images', 'textdomain' ), array($this, 'uig_my_display_callback' ), 'uig_image_gallery' );
        add_meta_box('uig_shortcode_metabox','Shortcode', array( $this, 'uig_shortcode_metabox_callback' ),'uig_image_gallery','side','high');
    }
    

    /**
     * Meta box display callback.
     *
     * @param WP_Post $post Current post object.
     */
    function uig_my_display_callback( $post ) {
    ?>
    
<!--
    <ul class="uig-zoom-gallery-metabox">
        <?php
        
            $image_ids = !empty(get_post_meta( $post->ID, 'uig_gallery_image_ids', true )) ? explode(',',get_post_meta( $post->ID, 'uig_gallery_image_ids', true )) : array();
        
            $uig_header_title = get_post_meta( $post->ID, 'uig_header_title', true );
        
            if( !is_array($image_ids) ){
                $image_ids = array();
            }
        
            foreach( $image_ids as $i => &$id ) {
                $url = wp_get_attachment_image_url( $id );
                if( $url ) {
                    ?>
                        <li data-id="<?php echo $id ?>">
                            <span class="uig-zoom-gallery-image" style="background-image:url('<?php echo $url ?>')"></span>
                            <a href="#" title="Remove" class="uig-gallery-image-remove">&times;</a>
                        </li>
                    <?php
                } else {
                    unset( $image_ids[ $i ] );
                }
            }
        ?>
    </ul>
    <input type="hidden" name="uig_gallery_image_ids" class="uig-image-ids" value="<?php echo join( ',', $image_ids ) ?>" />
    <a href="#" class="button uig-zoom-gallery-upload-button">Add Images</a>
-->
    
    
    <!--Filter type-->
    <h4 class="uig-gallery-settings-heading">Select gallery type</h4>
    <div class="uig-filter-type-tabs">
        <input class="uig_gallery_type" type="radio" name="uig_gallery_type" id="uig_type_image_gallery" checked value="image_gallery">
        <label for="uig_type_image_gallery" class="uig-filter-type-tab">Image gallery</label>
        <input class="uig_gallery_type" type="radio" name="uig_gallery_type" id="uig_type_filterable_gallery" value="filterable_gallery">
        <label for="uig_type_filterable_gallery" class="uig-filter-type-tab">Filterable image gallery</label>
    </div>
    
    <hr>
   
   <h4 class="uig-gallery-settings-heading left-align">Add gallery images</h4>
    <div class="uig-repeater-container">
        <div class="uig-field-item-clone" style="display:none">
            <div class="uig-field-item">
               <div class="uig-repeater-action-buttons">
                   <span class="dashicons dashicons-move"></span>
                   <img class="uig-gallery-perview-image" src="">
                   <div><span class="toggle-button dashicons dashicons-arrow-up-alt2"></span><a href="#" class="uig-remove-field">Remove</a></div>
               </div>
                <div class="uig-gallery-fields-wrapper">
                    <ul>
                        <li>
                           <label class="uig-gallery-form-control-lebel">Upload Image or Enter URL</label>
                            <div class="uig-image-field-wrappper">
                                <input class="uig-gallery-form-control uig-image-url" type="text" name="uig-gallery-image-url[]" value="" placeholder="Enter image URL"><a class="uig-gallery-image-upload button button-primary button-large uig-image-upload" href="#">Upload</a>
                            </div>
                        </li>
                        <li>
                           <label class="uig-gallery-form-control-lebel">Image Title</label>
                            <input class="uig-gallery-form-control" type="text" name="uig-image-title" value="" placeholder="Enter image title">
                        </li>
                        <li class="filter_category_field hidden-if-image-gallery">
                            <label class="uig-gallery-form-control-lebel">Filter Category</label>
                            <?php
                            $args = array(
                                'taxonomy' => 'uig-filter-category',
                                'name' => 'uig-filter-category',
                                'class' => 'uig-filter-category uig-gallery-form-control',
                                'show_option_none' => 'Select a category',
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
            <div class="uig-field-item">
               <div class="uig-repeater-action-buttons">
                   <span class="dashicons dashicons-move"></span>
                   <img class="uig-gallery-perview-image" src="">
                   <div><span class="toggle-button dashicons dashicons-arrow-up-alt2"></span><a href="#" class="uig-remove-field">Remove</a></div>
               </div>
                <div class="uig-gallery-fields-wrapper">
                    <ul>
                        <li>
                           <label class="uig-gallery-form-control-lebel">Upload Image or Enter URL</label>
                            <div class="uig-image-field-wrappper">
                                <input class="uig-gallery-form-control uig-image-url" type="text" name="uig-gallery-image-url[]" value="" placeholder="Enter image URL"><a class="uig-gallery-image-upload button button-primary button-large uig-image-upload" href="#">Upload</a>
                            </div>
                        </li>
                        <li>
                           <label class="uig-gallery-form-control-lebel">Image Title</label>
                            <input class="uig-gallery-form-control" type="text" name="uig-image-title" value="" placeholder="Enter image title">
                        </li>
                        <li class="filter_category_field hidden-if-image-gallery">
                            <label class="uig-gallery-form-control-lebel">Filter Category</label>
                            <?php
                            $args = array(
                                'taxonomy' => 'uig-filter-category',
                                'name' => 'uig-filter-category',
                                'class' => 'uig-filter-category uig-gallery-form-control',
                                'show_option_none' => 'Select a category',
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
        <div class="uig-add-more-wrapper"><a href="#" id="uig-add-field"><span class="dashicons dashicons-plus-alt2"></span> Add image</a></div>
    </div>
    
    
    
    
    <hr>
    <p>
        <label for="display-title"><input id="display-title" name="uig_header_title" type="checkbox" value="yes" <?php echo checked( 'yes', $uig_header_title, true ); ?> > Display header title</label>
    </p>
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
        if( isset($_POST['uig_gallery_image_ids']) ){
            update_post_meta( $post_id, 'uig_gallery_image_ids', $_POST['uig_gallery_image_ids'] );
        }
        
        if( isset($_POST['uig_header_title']) ){
            update_post_meta( $post_id, 'uig_header_title', $_POST['uig_header_title'] );
        }
        
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
            'label'        => __( 'Filter category', 'textdomain' ),
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
        $uig_header_title = get_post_meta( $id, 'uig_header_title', true );
        ?>
        <div class="img-viewer">
          
           <?php if( $uig_header_title == 'yes' ) : ?>
            <h2>
                <?php echo get_the_title($id); ?>
            </h2>
            <?php endif; ?>
            <div>
               
                <ul id="images">
                    <?php 
                    $image_ids = ( $image_ids = get_post_meta( $id, 'uig_gallery_image_ids', true ) ) ? explode(',',$image_ids) : array();
        
                    if( !is_array($image_ids) ){
                        $image_ids = array();
                    }

                    foreach( $image_ids as $i => $image_id ) {
                        $url = wp_get_attachment_image_url( $image_id, 'full' );
                        if( $url ) {
                            ?>
                                <li>
                                    <img src="<?php echo esc_url($url) ?>" alt="">
                                    <h2 class="uig-image-title">Image title</h2>
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
                                    <li class="uig-filter-item <?php echo esc_attr($terms_string); ?>">
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
