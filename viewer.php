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
        
		require_once plugin_dir_path( __FILE__ ) . 'includes/admin.php';
		
        add_action('init', array($this, 'ultimate_image_gallery_function'));
        add_action('wp_enqueue_scripts', array($this,'ultimate_image_gallery_scripts'));
        add_action('admin_enqueue_scripts', array($this,'admin_scripts'));
        add_shortcode('uig_image_gallery', array($this, 'ultimate_image_gallery_scode'));
        add_shortcode('uig_filter_gallery', array($this, 'uig_filter_gallery_scode'));
        
        add_action( 'init', array($this, 'uig_register_private_taxonomy') );
        
        // admin column
        add_filter('manage_uig_image_gallery_posts_columns', array($this, 'uig_custom_columns' ), 10);
        add_action('manage_posts_custom_column', array($this, 'uig_custom_columns_shortcode' ), 10, 2);
        
        define('BG_CSS_URI', trailingslashit('assets/css'));
        define('BG_JS_URI', trailingslashit('assets/js'));
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
    
    public function uig_register_private_taxonomy() {
        $taxonomy = 'uig-filter-category';
		$args = array(
            'label'        => __( 'Filter category', 'ultimate_image_gallery' ),
            'public'       => true,
            'rewrite'      => false,
            'hierarchical' => true
        );

        register_taxonomy( $taxonomy, 'uig_image_gallery', $args );
		
		//Create Uncategorized term
		$uncategorized = array(
			'name' => 'Uncategorized',
			'slug' => 'uncategorized',
		);
		
		$term = wp_insert_term( $uncategorized['name'], $taxonomy, array(
			'slug' => $uncategorized['slug'],
		));
		
		// Set the term as default for the taxonomy
		if ( !is_wp_error( $term ) ) {
			update_option($taxonomy . "_default", $term['term_id']);
		}
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

        require_once plugin_dir_path( __FILE__ ) . 'templates/gallery.php';

    	return ob_get_clean();
    }
    
    public function uig_filter_gallery_scode($img_attr, $img_content = null){
        //Shortcode [uig_image_gallery title="your title" description="Description"][/uig_image_gallery]
        $scode_atts = shortcode_atts(array(
                'id'=>''
            ),$img_attr);
        extract($scode_atts);

        ob_start();
        
        
		
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
