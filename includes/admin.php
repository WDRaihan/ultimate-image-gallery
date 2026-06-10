<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit();
}

class UIG_ADMIN_FUNCTIONS {
	
	public function __construct(){
		//Gallery post meta boxes
		add_action( 'add_meta_boxes', array($this,'uig_register_meta_boxes') );
		add_action( 'save_post', array($this,'uig_save_meta_box' ), 10, 2 );
		//Posts column
        add_filter('manage_uig_image_gallery_posts_columns', array($this, 'uig_custom_columns' ), 10);
        add_action('manage_posts_custom_column', array($this, 'uig_custom_columns_shortcode' ), 10, 2);
	}

	/**
     * Register meta box(es).
     */
    public function uig_register_meta_boxes() {
        add_meta_box( 'uig_gallery_metabox', __( 'Ultimate Gallery', 'ultimate_image_gallery' ), array($this, 'uig_gallery_metabox_callback' ), 'uig_image_gallery' );
    }

    /**
     * Meta box display callback.
     *
     * @param WP_Post $post Current post object.
     */
    function uig_gallery_metabox_callback( $post ) {
   		require_once('meta-fields.php');
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
		
        $postdata = wp_unslash( $_POST );
        
		//Gallery type
		if( isset($_POST['uig_gallery_type']) ){
            update_post_meta( $post_id, 'uig_gallery_type', sanitize_text_field( $_POST['uig_gallery_type'] ) );
        }
		
		//Gallery content: image, title, category
		$all_items = array();
		foreach($_POST['uig_gallery_image_url'] as $k=>$item){
			
			if( !empty(array_filter($_POST['uig_filter_category'][$k])) ){
				$filter_category = array_map( 'intval', (array) $_POST['uig_filter_category'][$k] );
			}else{
				$filter_category = array();
			}
			
			$description = isset( $_POST['uig_image_description'][$k] ) ? wp_kses( $_POST['uig_image_description'][$k], array( 'br' => array() ) ) : '';
			
			$all_items[] = array(
				'image_url'	=> sanitize_url( $item ),
				'image_title' => sanitize_text_field( $_POST['uig_image_title'][$k] ),
                'image_description' => $description,
				'filter_category' => $filter_category
			);
		}

        $items_data = apply_filters('uig_update_gallery_items_data', $all_items, $postdata);

		update_post_meta( $post_id, 'uig_gallery_items', $items_data );
		
		//Save meta fields
		$meta_fields = $this->uig_meta_field_names();
		
		if(is_array($meta_fields) && !empty($meta_fields)){
			foreach( $meta_fields as $field_name=>$field_type ){
				$field_value = isset( $postdata[$field_name] ) ? sanitize_text_field( $postdata[$field_name] ) : '';
				update_post_meta( $post_id, $field_name, $field_value );
			}
		}
        
    }
	
	/**
     * Posts column
     *
     * Name: Shortcode
     */
	public function uig_custom_columns($columns) {
        
        $columns['uig_gallery_shortcode'] = esc_html__('Shortcode', 'ultimate_image_gallery');
        unset($columns['date']);
        $columns['date'] = __( 'Date' );
        
        return $columns;
    }
    
	/**
     * Posts column
     *
     * Display gallery shortcode
     */
    public function uig_custom_columns_shortcode($column_name, $id){  
        if($column_name === 'uig_gallery_shortcode') { 
            $shortcode = UIG_GALLERY_SHORTCODE . ' id="' . $id . '"';
            echo "<input type='text' readonly value='[".esc_attr($shortcode)."]'>";
        }
    }
	
	/**
     * Meta field names array
     */
    public function uig_meta_field_names(){
        
		$fields = array(
			'uig_masonry_layout' 			=> 'text',
			'uig_gallery_column' 			=> 'text',
			'uig_gallery_item_space' 		=> 'text',
			'uig_display_image_title' 		=> 'text',
			'uig_display_image_description' => 'text',
			'uig_enable_image_zoom' 		=> 'text',
			'uig_border_radius' 			=> 'text',
			'uig_image_info_layout' 		=> 'text',
			'uig_filter_buttons_alignment' 	=> 'text',
			'uig_filter_all_button_text' 	=> 'text',
			'uig_filter_button_border_radius'=> 'text',
			'uig_filter_button_bg_color' 	=> 'text',
			'uig_filter_button_text_color'=> 'text',
			'uig_filter_button_active_bg_color' => 'text',
			'uig_filter_button_active_text_color' => 'text',
		);
		
		return apply_filters('uig_meta_field_names', $fields);
    }
}
new UIG_ADMIN_FUNCTIONS();
