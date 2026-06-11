<?php
/**
 * Pro Feature: Gallery image info layout styles.
 *
 * Adds an admin-selectable layout for image title/description display.
 *
 * @package UltimateImageGallery
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit();
}

class UIG_Pro_Gallery_Info_Layout {

    public function __construct() {
        add_filter( 'uig_meta_field_names', array( $this, 'register_meta_fields' ) );
        add_action( 'uig_style_settings_fields', array( $this, 'render_style_setting' ) );
        add_filter( 'uig_gallery_images_classes', array( $this, 'add_gallery_layout_class' ), 10, 2 );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ), 20 );
    }

    /**
     * Register the layout setting so the core save handler persists it.
     */
    public function register_meta_fields( $fields ) {
        $fields['uig_image_info_layout'] = 'text';
        return $fields;
    }

    /**
     * Render the Style Settings row.
     */
    public function render_style_setting( $post ) {
        $layout = get_post_meta( $post->ID, 'uig_image_info_layout', true );
        $layout = ! empty( $layout ) ? $layout : 'overlay';
        $layouts = apply_filters( 'uig_image_info_layouts', array(
            'overlay'     => esc_html__( 'Overlay on Image', 'ultimate_image_gallery' ),
            'below-image' => esc_html__( 'Content Below Image', 'ultimate_image_gallery' ),
        ) );
        ?>
        <tr>
            <td>
                <label for="image_info_layout"><strong><?php esc_html_e( 'Image Info Layout', 'ultimate_image_gallery' ); ?></strong></label>
            </td>
            <td>
                <select id="image_info_layout" class="uig-form-field" name="uig_image_info_layout">
                    <?php foreach ( $layouts as $value => $label ) : ?>
                        <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $layout ); ?>><?php echo esc_html( $label ); ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <?php
    }

    /**
     * Add the selected layout class to the gallery image wrapper.
     */
    public function add_gallery_layout_class( $classes, $gallery_id ) {
        $layout = get_post_meta( $gallery_id, 'uig_image_info_layout', true );
        $layout = ! empty( $layout ) ? $layout : 'overlay';
        $classes[] = 'uig_info_layout_' . str_replace( '-', '_', $layout );
        return $classes;
    }

    /**
     * Add frontend CSS for the pro layout.
     */
    public function enqueue_frontend_assets() {
        $css = '
/* UIG Pro: Image info layout - content below image */
.uig-img-viewer .uig_info_layout_below .uig-gallery-item {
    background: #fff;
    border: 1px solid #e5e7eb;
    box-sizing: border-box;
    line-height: normal;
}

.uig-img-viewer .uig_info_layout_below .uig-gallery-item img {
    display: block;
    height: auto;
}

.uig-img-viewer .uig_info_layout_below .uig-item-meta,
.uig-img-viewer .uig_info_layout_below .uig-item-meta.uig-item-meta-with-description:hover {
    position: static;
    z-index: 1;
    padding: 12px 14px 14px;
    background: #fff;
    width: 100%;
    top: auto;
    bottom: auto;
    margin-top: 0;
    box-sizing: border-box;
    transition: none;
}

.uig-img-viewer .uig_info_layout_below .uig-item-meta .uig-image-title {
    color: #1f2937;
    line-height: 1.35;
    margin: 0;
}

.uig-img-viewer .uig_info_layout_below .uig-item-meta p.uig-image-description {
    color: #4b5563;
    line-height: 1.6;
    margin: 7px 0 0;
}
';
        wp_add_inline_style( 'uig-styles', $css );
    }
}

new UIG_Pro_Gallery_Info_Layout();
