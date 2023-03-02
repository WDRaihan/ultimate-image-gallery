<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit();
}

class UIG_Gallery_Functions {
	
	public function __construct(){
        
        add_action( 'init', array($this, 'init_plugin') );
		add_action('wp_enqueue_scripts', array($this,'ultimate_image_gallery_scripts'));
		add_action('admin_enqueue_scripts', array($this,'admin_scripts'));
        add_shortcode(UIG_GALLERY_SHORTCODE, array($this, 'ultimate_image_gallery_scode'));
        add_action( 'init', array($this, 'uig_register_taxonomy') );
    }
	
	//Initializes the plugin
    public function init_plugin(){
        
		//Register post type
        register_post_type('uig_image_gallery', array(
            'labels'=>array(
                'name'=>'Ultimate Gallery',
                'all_items'=> __( 'All Galleries', 'ultimate_image_gallery' ),
                'add_new'=> __( 'Add Gallery', 'ultimate_image_gallery' ),
                'add_new_item' => __( 'Add new Gallery', 'ultimate_image_gallery' ),
                'edit_item'  => __( 'Edit Gallery', 'ultimate_image_gallery' ),
                'view_items' => __( 'View Galleries', 'ultimate_image_gallery' ),
                'not_found' => __( 'No gallery found', 'ultimate_image_gallery' ),
                'not_found_in_trash' => __( 'No gallery found in trash', 'ultimate_image_gallery' )
            ),
            'public'=>true,
            'menu_icon'=>'dashicons-images-alt2',
            'supports'=>array('title')
        ));
        
    }
	
	//Register taxonomy
	public function uig_register_taxonomy() {
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
    
	//Enqueue scripts
    public function ultimate_image_gallery_scripts(){
        //CSS style
        wp_enqueue_style('uig-viewercss', UIG_CSS_URI.'/viewer.css');
        wp_enqueue_style('uig-imageviewercss', UIG_CSS_URI.'/imageviewer.css');
        
        //JS script
        wp_enqueue_script('uig-viewerjs', UIG_JS_URI.'/viewer.js',array('jquery'),null,true);
        wp_enqueue_script('uig-imageviewerjs', UIG_JS_URI.'/imageviewer.js',array('jquery'),null,true);        
    }
	
	//Enqueue admin scripts
    public function admin_scripts(){
        //CSS style
        wp_enqueue_style('uig-admin-styles', UIG_PLUGIN_ASSEST.'admin/css/admin-style.css');
        //JS script
        wp_enqueue_script('uig-admin-scripts', UIG_PLUGIN_ASSEST.'admin/js/admin-scripts.js',array('jquery'),null,true);
    }
	
    //Gallery shortcode
    public function ultimate_image_gallery_scode($img_attr, $img_content){
        $scode_atts = shortcode_atts(array(
                'id'=>''
            ),$img_attr);
        extract($scode_atts);

        ob_start();

        include plugin_dir_path( __FILE__ ) . '../templates/gallery.php';

    	return ob_get_clean();
    }
}

new UIG_Gallery_Functions();