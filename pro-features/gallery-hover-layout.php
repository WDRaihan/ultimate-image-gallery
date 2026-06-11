<?php
/**
 * Pro Feature: Gallery Slide-up Hover Layout style.
 *
 * Adds a premium hover-based layout for image details display.
 *
 * @package UltimateImageGallery
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit();
}

class UIG_Pro_Gallery_Hover_Layout {

    public function __construct() {
        add_filter( 'uig_image_info_layouts', array( $this, 'register_layout' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ), 25 );
    }

    /**
     * Register the new layout inside the layouts array.
     */
    public function register_layout( $layouts ) {
        $layouts['hover-slide-up'] = esc_html__( 'Slide up on Hover', 'ultimate_image_gallery' );
        return $layouts;
    }

    /**
     * Enqueue inline CSS for the new layout.
     */
    public function enqueue_frontend_assets() {
        $css = '
/* ---- UIG Pro: Slide-up Hover Layout styles ---- */
.uig-img-viewer .uig_info_layout_hover_slide_up .uig-gallery-item {
    position: relative !important;
    overflow: hidden !important;
}

.uig-img-viewer .uig_info_layout_hover_slide_up .uig-item-meta {
    position: absolute !important;
    top: auto !important;
    bottom: 0 !important;
    left: 0 !important;
    right: 0 !important;
    margin-top: 0 !important;
    width: 100% !important;
    box-sizing: border-box !important;
    padding: 18px 16px 20px !important;
    background: rgba(15, 23, 42, 0.88) !important; /* Premium Slate-900 background with transparency */
    backdrop-filter: blur(12px) !important;
    -webkit-backdrop-filter: blur(12px) !important;
    border-top: 1px solid rgba(255, 255, 255, 0.12) !important;
    transform: translateY(100.5%) !important;
    transition: transform 0.45s cubic-bezier(0.25, 0.46, 0.45, 0.94) !important;
    opacity: 0 !important;
    visibility: hidden !important;
}

/* On hover, slide up and show details */
.uig-img-viewer .uig_info_layout_hover_slide_up .uig-gallery-item:hover .uig-item-meta {
    transform: translateY(0) !important;
    opacity: 1 !important;
    visibility: visible !important;
}

/* Disable default hover behavior to prevent conflicts */
.uig-img-viewer .uig_info_layout_hover_slide_up .uig-item-meta.uig-item-meta-with-description:hover {
    top: auto !important;
    margin-top: 0 !important;
}

/* Image zoom/scale effect on hover */
.uig-img-viewer .uig_info_layout_hover_slide_up .uig-gallery-item img {
    transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) !important;
}

.uig-img-viewer .uig_info_layout_hover_slide_up .uig-gallery-item:hover img {
    transform: scale(1.08) !important;
}

/* Typography styles for modern aesthetic */
.uig-img-viewer .uig_info_layout_hover_slide_up .uig-item-meta .uig-image-title {
    color: #ffffff !important;
    font-size: 18px !important;
    font-weight: 600 !important;
    line-height: 1.35 !important;
    margin: 0 0 6px 0 !important;
    text-align: left !important;
}

.uig-img-viewer .uig_info_layout_hover_slide_up .uig-item-meta p.uig-image-description {
    color: #e2e8f0 !important; /* Slate-200 */
    font-size: 13.5px !important;
    line-height: 1.5 !important;
    margin: 0 !important;
    text-align: left !important;
}
';
        wp_add_inline_style( 'uig-styles', $css );
    }
}

new UIG_Pro_Gallery_Hover_Layout();
