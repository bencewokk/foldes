<?php
/**
 * Theme functions file
 *
 * @package My_Custom_Theme
 */

// Enqueue stylesheets and scripts
function foldestheme_enqueue_styles() {
    // Main theme stylesheet
    wp_enqueue_style( 'foldestheme-style', get_stylesheet_uri() );

    // Additional CSS files
    wp_enqueue_style( 'foldestheme-reset', get_template_directory_uri() . '/css/reset.css' );
    wp_enqueue_style( 'foldestheme-main', get_template_directory_uri() . '/css/main.css' );
    wp_enqueue_style( 'foldestheme-colors', get_template_directory_uri() . '/css/colors.css' );
    wp_enqueue_style( 'foldestheme-header', get_template_directory_uri() . '/css/header.css' );
    wp_enqueue_style( 'foldestheme-footer', get_template_directory_uri() . '/css/footer.css' );
    wp_enqueue_style( 'foldestheme-responsive', get_template_directory_uri() . '/css/responsive.css' );
    wp_enqueue_style( 'foldestheme-search', get_template_directory_uri() . '/css/search.css' );

    wp_enqueue_style( 'foldestheme-layout', get_template_directory_uri() . '/css/layout.css' );

    // Enqueue post-specific styles
    wp_enqueue_style( 'foldestheme-posts', get_template_directory_uri() . '/css/posts.css' );
}
add_action( 'wp_enqueue_scripts', 'foldestheme_enqueue_styles' );

// Theme setup function
function foldestheme_setup() {
    // Enable support for post thumbnails (featured images)
    add_theme_support( 'post-thumbnails' );

    // Register navigation menus
    register_nav_menus( array(
        'primary' => 'Primary Menu',
        'footer'  => 'Footer Menu',
    ) );
}
add_action( 'after_setup_theme', 'foldestheme_setup' );

// Register widget areas (sidebars)
function foldestheme_widgets_init() {
    // Example widget area for footer
    register_sidebar( array(
        'name'          => 'Footer Widget Area',
        'id'            => 'footer-widget-area',
        'before_widget' => '<div class="footer-widget-area">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'foldestheme_widgets_init' );

// Custom excerpt length
function foldestheme_excerpt_length( $length ) {
    return 20; // Adjust the number of words in the excerpt
}
add_filter( 'excerpt_length', 'foldestheme_excerpt_length' );
