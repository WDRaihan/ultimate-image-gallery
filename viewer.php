<?php 

/*
Plugin Name: Ultimate Image Gallery - Image Zoom, Viewer, Lightbox and Filter Gallery
Author: wdraihan
Description:This is a beautiful responsive image gallery. You can see each image with 10000% zoom and it has  more features. After installing it, a Gallery menu will be created on your WordPress dashboard, form which you can easily set the image. You can use it as a portfolio gallery, photo gallery,  photo album, image gallery, widget image gallery etc. (You have to use this shortcode "[uig_image_gallery] or [uig_image_gallery title="your title" description="Your description"][/uig_image_gallery]" in your page to show the gallery).
Version: 1.0.0
Text Domain: ultimate_image_gallery
Domain Path: /languages
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit();
}

class UIG_Ultimate_Image_Gallery {
    
	/**
     * Plugin version
     *
     * @var string
     */
    public $version = '1.0.0';

	/**
     * Constructor for the UIG_Ultimate_Image_Gallery class
     */
    public function __construct(){
        define( 'UIG_GALLERY_SHORTCODE', 'uig_gallery' );
		define( 'UIG_PLUGIN_ASSEST', trailingslashit(plugins_url( 'assets', __FILE__ )) );
		define( 'UIG_CSS_URI', UIG_PLUGIN_ASSEST.'css' );
        define( 'UIG_JS_URI', UIG_PLUGIN_ASSEST.'js' );
		
        add_action('init', array($this, 'localization_setup'));
		
		//Require gallery functions
		require_once plugin_dir_path( __FILE__ ) . 'includes/gallery-functions.php';

		//Require admin functions
		require_once plugin_dir_path( __FILE__ ) . 'includes/admin.php';
    }
	
	/**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
	public function localization_setup(){
		load_plugin_textdomain('ultimate_image_gallery', false, dirname(__FILE__).'/languages');
	}
    
}
new UIG_Ultimate_Image_Gallery();
