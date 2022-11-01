<?php 

/*
Plugin Name: Ultimate Gallery - Image Viewer, Zoom and Filter Gallery
Author: Raihan
Description:This is a beautiful responsive image gallery. You can see each image with 10000% zoom and it has  more features. After installing it, a Gallery menu will be created on your WordPress dashboard, form which you can easily set the image. You can use it as a portfolio gallery, photo gallery,  photo album, image gallery, widget image gallery etc. (You have to use this shortcode "[rai-image-viewer] or [rai-image-viewer title="your title" description="Your description"][/rai-image-viewer]" in your page to show the gallery).
Version: 1.0
Text Domain: rai_image_viewer
Domain Path: /languages
*/


class Rai_image_class {
    
    public function __construct(){
        
        add_action( 'add_meta_boxes', array($this,'wpdocs_register_meta_boxes') );
        add_action( 'save_post', array($this,'wpdocs_save_meta_box' ), 10, 2 );
        
        add_action('init', array($this, 'rai_image_viewer_function'));
        add_action('wp_enqueue_scripts', array($this,'rai_image_viewer_scripts'));
        add_action('admin_enqueue_scripts', array($this,'admin_scripts'));
        add_shortcode('rai-image-viewer', array($this, 'rai_image_viewer_scode'));
        add_shortcode('rai-filter-gallery', array($this, 'rai_filter_gallery_scode'));
        
        add_action( 'init', array($this, 'wpdocs_register_private_taxonomy'), 0 );
        
        // admin column
        add_filter('manage_rai-image-post_posts_columns', array($this, 'rai_custom_columns' ), 10);
        add_action('manage_posts_custom_column', array($this, 'rai_custom_columns_shortcode' ), 10, 2);
        
        define('BG_CSS_URI',trailingslashit('assets/css'));
        define('BG_JS_URI',trailingslashit('assets/js'));
    }
    
    
    /**
     * Register meta box(es).
     */
    public function wpdocs_register_meta_boxes() {
        add_meta_box( 'zoom-gallery-images', __( 'Zoom Gallery images', 'textdomain' ), array($this, 'rai_my_display_callback' ), 'rai-image-post' );
        add_meta_box('rai_shortcode_metabox','Shortcode', array( $this, 'rai_shortcode_metabox_callback' ),'rai-image-post','side','high');
    }
    

    /**
     * Meta box display callback.
     *
     * @param WP_Post $post Current post object.
     */
    function rai_my_display_callback( $post ) {
    ?>
    
    <ul class="rai-zoom-gallery-metabox">
        <?php
        
            $image_ids = ( $image_ids = get_post_meta( $post->ID, 'rai_gallery_image_ids', true ) ) ? explode(',',$image_ids) : array();
        
            $rai_header_title = get_post_meta( $post->ID, 'rai_header_title', true );
        
            if( !is_array($image_ids) ){
                $image_ids = array();
            }
        
            foreach( $image_ids as $i => &$id ) {
                $url = wp_get_attachment_image_url( $id );
                if( $url ) {
                    ?>
                        <li data-id="<?php echo $id ?>">
                            <span class="rai-zoom-gallery-image" style="background-image:url('<?php echo $url ?>')"></span>
                            <a href="#" title="Remove" class="rai-gallery-image-remove">&times;</a>
                        </li>
                    <?php
                } else {
                    unset( $image_ids[ $i ] );
                }
            }
        ?>
    </ul>
    <input type="hidden" name="rai_gallery_image_ids" class="rai-image-ids" value="<?php echo join( ',', $image_ids ) ?>" />
    <a href="#" class="button rai-zoom-gallery-upload-button">Add Images</a>
    <hr>
    <p>
        <label for="display-title"><input id="display-title" name="rai_header_title" type="checkbox" value="yes" <?php echo checked( 'yes', $rai_header_title, true ); ?> > Display header title</label>
    </p>
    <?php
    }
    
    public function rai_shortcode_metabox_callback(){
        $bafg_scode = isset($_GET['post']) ? '[rai-image-viewer id="'.$_GET['post'].'"]' : '';
        ?>
        <input type="text" name="bafg_display_shortcode" class="rai_display_shortcode" value="<?php echo esc_attr($bafg_scode); ?>" readonly>

        <div id="rai_shortcode_copied_notice">Shortcode Copied!</div>
        
        
        <?php
    }

    /**
     * Save meta box content.
     *
     * @param int $post_id Post ID
     */
    public function wpdocs_save_meta_box( $post_id, $post ) {
        update_post_meta( $post_id, 'rai_gallery_image_ids', $_POST['rai_gallery_image_ids'] );
        update_post_meta( $post_id, 'rai_header_title', $_POST['rai_header_title'] );
    }
    
    
    public function rai_image_viewer_function(){
        load_plugin_textdomain('rai_image_viewer', false, dirname(__FILE__).'/language');
        
        register_post_type('rai-image-post',array(
            'labels'=>array(
                'name'=>'BG Gallery',
                'all_items'=>'All Image',
                'add_new'=>'Add Image',
                'add_new_item' => 'Add new Image',
                'edit_item'  => 'Edit Image',
                'view_items' => 'View Images',
                'not_found' => 'No image found',
                'not_found_in_trash' => 'No image found in trash',
            ),
            'public'=>true,
            'menu_icon'=>'dashicons-format-image',
            'supports'=>array('title')
        ));
    }
    
    function wpdocs_register_private_taxonomy() {
        $args = array(
            'label'        => __( 'Filter category', 'textdomain' ),
            'public'       => true,
            'rewrite'      => false,
            'hierarchical' => true
        );

        register_taxonomy( 'filter-category', 'rai-image-post', $args );
    }
    
    
    public function rai_custom_columns($columns) {
        
        $columns['rai_gallery_shortcode'] = esc_html__('Shortcode', 'bafg');
        unset($columns['date']);
        $columns['date'] = __( 'Date' );
        
        return $columns;
    }
    
    function rai_custom_columns_shortcode($column_name, $id){  
        if($column_name === 'rai_gallery_shortcode') { 
            $post_id =	$id;
            $shortcode = 'rai-image-viewer id="' . $post_id . '"';
            echo "<input type='text' readonly value='[".$shortcode."]'>";
        }
    }
    
    public function rai_image_viewer_scripts(){
        //CSS style
        wp_enqueue_style('rai-viewercss', plugins_url(BG_CSS_URI.'viewer.css',__FILE__));
        wp_enqueue_style('rai-imageviewercss', plugins_url(BG_CSS_URI.'imageviewer.css',__FILE__));
        
        //JS script
        wp_enqueue_script('rai-isotope.pkgd.min', '//unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js',array('jquery'),null,true);
        
        
        wp_enqueue_script('rai-viewerjs', plugins_url(BG_JS_URI.'viewer.js',__FILE__),array('jquery'),null,true);
        wp_enqueue_script('rai-imageviewerjs', plugins_url(BG_JS_URI.'imageviewer.js',__FILE__),array('jquery','rai-isotope.pkgd.min'),null,true);
        
        /*wp_enqueue_script('rai-isotope.pkgd', '//unpkg.com/isotope-layout@3/dist/isotope.pkgd.js',array('jquery'),null,true);*/
        
    }
    
    public function admin_scripts(){
        //CSS style
        wp_enqueue_style('rai-admin-styles', plugins_url('assets/admin/css/admin-style.css',__FILE__));
        
        //JS script
        wp_enqueue_script('rai-admin-scripts', plugins_url('assets/admin/js/admin-scripts.js',__FILE__),array('jquery'),null,true);

    }
    
    
    public function rai_image_viewer_scode($img_attr, $img_content){
        //Shortcode [rai-image-viewer title="your title" description="Description"][/rai-image-viewer]
        $scode_atts = shortcode_atts(array(
                'id'=>''
            ),$img_attr);
            extract($scode_atts);

        ob_start();
        $rai_header_title = get_post_meta( $id, 'rai_header_title', true );
        ?>
        <div class="img-viewer">
          
           <?php if( $rai_header_title == 'yes' ) : ?>
            <h2>
                <?php echo get_the_title($id); ?>
            </h2>
            <?php endif; ?>
            <div>
               
                <ul id="images">
                    <?php 
                    $image_ids = ( $image_ids = get_post_meta( $id, 'rai_gallery_image_ids', true ) ) ? explode(',',$image_ids) : array();
        
                    if( !is_array($image_ids) ){
                        $image_ids = array();
                    }

                    foreach( $image_ids as $i => $image_id ) {
                        $url = wp_get_attachment_image_url( $image_id, 'full' );
                        if( $url ) {
                            ?>
                                <li>
                                    <img src="<?php echo esc_url($url) ?>" alt="">
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
    
    public function rai_filter_gallery_scode($img_attr, $img_content = null){
        //Shortcode [rai-image-viewer title="your title" description="Description"][/rai-image-viewer]
        $scode_atts = shortcode_atts(array(
                'categories'=>'',
                'title' => ''
            ),$img_attr);
            extract($scode_atts);

        ob_start();
        
        $categories = explode(',',$categories);
        
        $args = array(
            'post_type' => 'rai-image-post',
            'tax_query' => array(
                array(
                    'taxonomy' => 'filter-category',
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
            
            <div class="rai-filter-gallery-wraper">
               
               <div class="rai-filter-buttons" data-isotope-key="filter">
                   <button class="rai-filter-button rai-filter-active" data-filter="*">show all</button>
                   
                   <?php 
                   foreach( $categories as $category_id ){
                       $term = get_term_by('id', $category_id, 'filter-category');
                       ?>
                       <button class="rai-filter-button" data-filter="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></button>
                        <?php
                   }
                   ?>
               </div>
               
                <ul id="images" class="rai_gallery_filter">
                    <?php 
                    
                    while( $query->have_posts() ){
                        $query->the_post();
                        $id = get_the_id();
                        
                        $image_ids = ( $image_ids = get_post_meta( $id, 'rai_gallery_image_ids', true ) ) ? explode(',',$image_ids) : array();
                        
                        $term_obj_list = get_the_terms( $id, 'filter-category' );
                        $terms_string = join(' ', wp_list_pluck($term_obj_list, 'slug'));
                        
                        foreach( $image_ids as $i => $image_id ) {
                            $url = wp_get_attachment_image_url( $image_id, 'full' );
                            if( $url ) {
                                ?>
                                    <li class="rai-filter-item <?php echo esc_attr($terms_string); ?>">
                                        <img src="<?php echo esc_url($url) ?>" alt="">
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
new Rai_image_class();