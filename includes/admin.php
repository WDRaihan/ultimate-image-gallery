<?php
class UIG_Meta_Fields {
	
	public function __construct(){
		
		add_action( 'add_meta_boxes', array($this,'uig_register_meta_boxes') );
		add_action( 'save_post', array($this,'uig_save_meta_box' ), 10, 2 );
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
   		require_once('meta-fields.php');
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
				'filter_category' => $_POST['uig_filter_category'][$k]
			);
		}
		update_post_meta( $post_id, 'uig_gallery_items', $all_items );
		
		//Display image title
        update_post_meta( $post_id, 'uig_display_image_title', esc_attr( $_POST['uig_display_image_title'] ) );
        
    }
}
new UIG_Meta_Fields();