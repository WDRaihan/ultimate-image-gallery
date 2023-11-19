<?php 
/*
* Plugin Name: Ultimate Image Gallery
* Description: This plugin provides a range of features to enhance the presentation of images on a website. It includes image zoom, viewer, lightbox, and filter gallery functionality.
* Version: 1.0.06
* Author: Raihan
* Text Domain: ultimate_image_gallery
* Domain Path: /languages
* License: GPL-2.0+
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
    public $version = '1.0.05';

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
