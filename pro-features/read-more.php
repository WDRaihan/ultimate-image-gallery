<?php
/**
 * Pro Feature: Read More Button for Image Descriptions
 *
 * - Description max length is a per-gallery setting.
 * - Read More mode (popup / page link) and page URL are per-image settings
 *   stored inside each item in uig_gallery_items.
 * - The modal is rendered outside the gallery item (appended to <body> via JS)
 *   to avoid overflow:hidden clipping.
 *
 * @package UltimateImageGallery
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit();
}

class UIG_Pro_Read_More {

    public function __construct() {

        // 1. Register gallery-level meta field (max description length)
        add_filter( 'uig_meta_field_names', array( $this, 'register_meta_fields' ) );

        // 2. Override the gallery metabox to append the max-length setting row
        add_action( 'add_meta_boxes', array( $this, 'override_metabox' ), 20 );

        // 3. Save per-image read-more data alongside each gallery item
        add_filter( 'uig_update_gallery_items_data', array( $this, 'save_per_image_data' ), 10, 2 );

        // 4. Filter the description paragraph on the frontend
        add_filter( 'uig_render_image_description', array( $this, 'render_description' ), 10, 4 );

        // 5. Enqueue frontend assets
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ), 20 );

        // 6. Enqueue admin assets (show/hide per-item URL field)
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ), 20 );
    }

    /* ------------------------------------------------------------------ */
    /*  Meta fields (gallery-level)                                         */
    /* ------------------------------------------------------------------ */

    /**
     * Register uig_description_max_length so core save handler persists it.
     */
    public function register_meta_fields( $fields ) {
        $fields['uig_description_max_length'] = 'text';
        return $fields;
    }

    /* ------------------------------------------------------------------ */
    /*  Admin metabox override (gallery-level max-length row)              */
    /* ------------------------------------------------------------------ */

    public function override_metabox() {
        remove_meta_box( 'uig_gallery_metabox', 'uig_image_gallery', 'advanced' );
        add_meta_box(
            'uig_gallery_metabox',
            __( 'Ultimate Gallery', 'ultimate_image_gallery' ),
            array( $this, 'metabox_callback' ),
            'uig_image_gallery'
        );
    }

    public function metabox_callback( $post ) {
        // Render original meta-fields content
        require plugin_dir_path( __FILE__ ) . '../includes/meta-fields.php';
        // Append gallery-level max-length setting
        $this->render_gallery_settings( $post );
    }

    public function render_gallery_settings( $post ) {
        $max_length = get_post_meta( $post->ID, 'uig_description_max_length', true );
        ?>
        <div class="uig-pro-readmore-settings" style="border-top:1px solid #ddd;margin-top:12px;padding-top:12px;">
            <h4 style="margin:0 0 10px;font-size:13px;color:#1d2327;">
                <?php esc_html_e( 'Read More Button Settings', 'ultimate_image_gallery' ); ?>
            </h4>
            <table class="uig-style-meta-table">
                <tbody>
                    <tr>
                        <td style="width:220px;">
                            <label for="uig_description_max_length">
                                <strong><?php esc_html_e( 'Description Max Length', 'ultimate_image_gallery' ); ?></strong>
                            </label>
                            <p style="margin:2px 0 0;color:#666;font-size:11px;">
                                <?php esc_html_e( 'Characters shown on image before "Read More". Leave empty to always show full description.', 'ultimate_image_gallery' ); ?>
                            </p>
                        </td>
                        <td>
                            <input
                                id="uig_description_max_length"
                                class="uig-form-field"
                                name="uig_description_max_length"
                                type="number"
                                min="10"
                                max="2000"
                                value="<?php echo esc_attr( $max_length ); ?>"
                                placeholder="<?php esc_attr_e( 'e.g. 80', 'ultimate_image_gallery' ); ?>"
                                style="width:90px;"
                            >
                            <span style="color:#666;font-size:12px;"><?php esc_html_e( 'chars', 'ultimate_image_gallery' ); ?></span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p style="margin:6px 0 0;color:#666;font-size:11px;">
                <?php esc_html_e( 'Per-image "Read More Mode" and "Page URL" are set inside each image item above.', 'ultimate_image_gallery' ); ?>
            </p>
        </div>
        <?php
    }

    /* ------------------------------------------------------------------ */
    /*  Save per-image data                                                 */
    /* ------------------------------------------------------------------ */

    /**
     * Attach per-image read-more settings to each gallery item on save.
     *
     * POST arrays expected:
     *   uig_item_readmore_mode[]        – 'popup' or 'page'
     *   uig_item_readmore_page_url[]    – URL string
     *
     * @param array $items
     * @param array $postdata wp_unslash($_POST)
     * @return array
     */
    public function save_per_image_data( $items, $postdata ) {
        foreach ( $items as $k => $item ) {
            $mode     = isset( $postdata['uig_item_readmore_mode'][ $k ] )
                        ? sanitize_text_field( $postdata['uig_item_readmore_mode'][ $k ] )
                        : 'popup';
            $page_url = isset( $postdata['uig_item_readmore_page_url'][ $k ] )
                        ? esc_url_raw( $postdata['uig_item_readmore_page_url'][ $k ] )
                        : '';

            $items[ $k ]['read_more_mode']     = $mode;
            $items[ $k ]['read_more_page_url'] = $page_url;
        }
        return $items;
    }

    /* ------------------------------------------------------------------ */
    /*  Frontend description rendering                                      */
    /* ------------------------------------------------------------------ */

    /**
     * Filter: uig_render_image_description
     *
     * @param string $default_output  Default <p> HTML
     * @param string $description     Raw description text
     * @param int    $gallery_id      Gallery post ID
     * @param array  $item_data       Full gallery item array (includes read_more_mode etc.)
     * @return string
     */
    public function render_description( $default_output, $description, $gallery_id, $item_data = array() ) {
        $max_length = (int) get_post_meta( $gallery_id, 'uig_description_max_length', true );
        $mode       = ! empty( $item_data['read_more_mode'] ) ? $item_data['read_more_mode'] : 'popup';
        $page_url   = ! empty( $item_data['read_more_page_url'] ) ? $item_data['read_more_page_url'] : '';

        // No max length configured → show full description unchanged
        if ( empty( $max_length ) || $max_length <= 0 ) {
            return $default_output;
        }

        $plain = wp_strip_all_tags( $description );

        // Description fits within limit → no button needed
        if ( mb_strlen( $plain ) <= $max_length ) {
            return $default_output;
        }

        // Truncate at word boundary
        $truncated  = mb_substr( $plain, 0, $max_length );
        $last_space = mb_strrpos( $truncated, ' ' );
        if ( $last_space !== false ) {
            $truncated = mb_substr( $truncated, 0, $last_space );
        }

        ob_start();
        ?>
        <p class="uig-image-description uig-desc-truncated">
            <?php echo esc_html( $truncated ); ?>&#8230;
            <?php if ( $mode === 'page' && ! empty( $page_url ) ) : ?>
                <a
                    href="<?php echo esc_url( $page_url ); ?>"
                    class="uig-read-more-link"
                    target="_blank"
                    rel="noopener noreferrer"
                ><?php esc_html_e( 'Read More', 'ultimate_image_gallery' ); ?></a>
            <?php else : ?>
                <button
                    type="button"
                    class="uig-read-more-btn"
                    data-description="<?php echo esc_attr( $plain ); ?>"
                    aria-expanded="false"
                ><?php esc_html_e( 'Read More', 'ultimate_image_gallery' ); ?></button>
            <?php endif; ?>
        </p>
        <?php
        return ob_get_clean();
    }

    /* ------------------------------------------------------------------ */
    /*  Assets                                                              */
    /* ------------------------------------------------------------------ */

    public function enqueue_frontend_assets() {
        wp_enqueue_script(
            'uig-read-more',
            plugins_url( 'assets/js/uig-read-more.js', dirname( __FILE__ ) ),
            array(),
            UIG_VERSION,
            true
        );

        $css = '
/* ---- UIG Read More: Truncated description ---- */
.uig-image-description.uig-desc-truncated {
    display: block;
}

/* Read More popup button */
.uig-read-more-btn {
    display: inline;
    background: none;
    border: none;
    padding: 0;
    margin: 0 0 0 4px;
    color: #4fc3f7;
    font-size: inherit;
    font-weight: 600;
    cursor: pointer;
    text-decoration: underline;
    text-underline-offset: 2px;
    line-height: inherit;
    vertical-align: baseline;
    transition: color 0.2s;
}
.uig-read-more-btn:hover,
.uig-read-more-btn:focus {
    color: #81d4fa;
    outline: none;
}

/* Read More page link */
.uig-read-more-link {
    display: inline;
    margin-left: 4px;
    color: #4fc3f7;
    font-weight: 600;
    text-decoration: underline;
    text-underline-offset: 2px;
    font-size: inherit;
    transition: color 0.2s;
}
.uig-read-more-link:hover {
    color: #81d4fa;
}

/* ---- Modal (appended to <body> by JS) ---- */
.uig-readmore-modal {
    position: fixed;
    inset: 0;
    z-index: 999999;
    display: flex;
    align-items: center;
    justify-content: center;
}
.uig-readmore-modal[hidden] {
    display: none !important;
}
.uig-readmore-modal-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.72);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    animation: uigRmBackdrop 0.22s ease forwards;
}
@keyframes uigRmBackdrop {
    from { opacity: 0; }
    to   { opacity: 1; }
}
.uig-readmore-modal-box {
    position: relative;
    z-index: 1;
    background: #1e1e2e;
    color: #cdd6f4;
    border-radius: 14px;
    padding: 36px 30px 28px;
    width: min(560px, 92vw);
    max-height: 80vh;
    overflow-y: auto;
    box-shadow: 0 28px 70px rgba(0,0,0,0.6);
    animation: uigRmBox 0.28s cubic-bezier(0.34,1.56,0.64,1) forwards;
    scrollbar-width: thin;
    scrollbar-color: #45475a transparent;
}
@keyframes uigRmBox {
    from { opacity: 0; transform: translateY(22px) scale(0.96); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
.uig-readmore-modal-close {
    position: absolute;
    top: 12px;
    right: 14px;
    background: rgba(255,255,255,0.09);
    border: none;
    color: #cdd6f4;
    font-size: 22px;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s, color 0.2s;
}
.uig-readmore-modal-close:hover {
    background: rgba(255,255,255,0.18);
    color: #fff;
}
.uig-readmore-modal-body {
    font-size: 15px;
    line-height: 1.8;
    color: #cdd6f4;
    text-align: left;
    word-break: break-word;
    white-space: pre-line;
}
';
        wp_add_inline_style( 'uig-styles', $css );
    }

    public function enqueue_admin_assets() {
        global $post_type;
        if ( 'uig_image_gallery' !== $post_type ) {
            return;
        }

        // JS: toggle the per-item URL input when the mode radio changes,
        // including for newly cloned items.
        $js = <<<'JS'
(function ($) {
    'use strict';

    // Show/hide URL row based on current radio value
    function uigUpdateReadMoreRow(radioInItem) {
        var $item = $(radioInItem).closest('.uig-field-item');
        var $urlWrap = $item.find('.uig-item-readmore-url-wrap');
        var val = $item.find('.uig-item-readmore-radio:checked').val();
        if (val === 'page') {
            $urlWrap.show();
        } else {
            $urlWrap.hide();
        }
    }

    $(document).on('change', '.uig-item-readmore-radio', function () {
        uigUpdateReadMoreRow(this);
    });

    // Also apply on page load for all existing items
    $(document).ready(function () {
        $('.uig-item-readmore-radio').each(function () {
            uigUpdateReadMoreRow(this);
        });
    });

}(window.jQuery));
JS;
        wp_add_inline_script( 'uig-admin-scripts', $js );
    }
}

new UIG_Pro_Read_More();
