<?php
/**
 * Theme functions file
 *
 * @package My_Custom_Theme
 */

// Enqueue stylesheets and scripts
function foldestheme_enqueue_styles() {
    wp_enqueue_style( 'foldestheme-style', get_stylesheet_uri() );
    wp_enqueue_style( 'foldestheme-reset', get_template_directory_uri() . '/css/reset.css' );
    wp_enqueue_style( 'foldestheme-main', get_template_directory_uri() . '/css/main.css' );
    wp_enqueue_style( 'foldestheme-colors', get_template_directory_uri() . '/css/colors.css' );
    wp_enqueue_style( 'foldestheme-header', get_template_directory_uri() . '/css/header.css' );
    wp_enqueue_style( 'foldestheme-footer', get_template_directory_uri() . '/css/footer.css' );
    wp_enqueue_style( 'foldestheme-responsive', get_template_directory_uri() . '/css/responsive.css' );
    wp_enqueue_style( 'foldestheme-search', get_template_directory_uri() . '/css/search.css' );
    wp_enqueue_style( 'foldestheme-tanaraink', get_template_directory_uri() . '/css/tanaraink.css' );
    wp_enqueue_style( 'foldestheme-layout', get_template_directory_uri() . '/css/layout.css' );
    wp_enqueue_style( 'foldestheme-posts', get_template_directory_uri() . '/css/posts.css' );

    // Enqueue Google Fonts
    wp_enqueue_style( 'google-fonts-roboto-poppins', 'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap', array(), null );
    
    // Enqueue custom font CSS
    wp_enqueue_style( 'custom-font-styles', get_template_directory_uri() . '/css/font-styles.css' );
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

// Theme Customizer Settings
function theme_customizer_settings($wp_customize) {
    $wp_customize->add_section('important_news_section', array(
        'title' => 'Fontos Hírek',
        'priority' => 30,
    ));

    $wp_customize->add_setting('important_news_text', array(
        'default' => '❗️ Aktuális hírek és értesítések...',
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('important_news_text', array(
        'label' => 'Hírek Szövege',
        'section' => 'important_news_section',
        'type' => 'textarea',
    ));
}
add_action('customize_register', 'theme_customizer_settings');

// Custom excerpt length
function foldestheme_excerpt_length( $length ) {
    return 20; // Adjust the number of words in the excerpt
}
add_filter( 'excerpt_length', 'foldestheme_excerpt_length' );

// Apply Roboto font site-wide via inline styles
function foldestheme_inline_styles() {
    echo "<style>
        body {
            font-family: 'Roboto', serif;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
            font-variation-settings: 'wdth' 100;
        }
    </style>";
}
add_action('wp_head', 'foldestheme_inline_styles');
