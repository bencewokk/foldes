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
    wp_enqueue_style( 'foldestheme-iskolankrol', get_template_directory_uri() . '/css/iskolankrol.css' );
    wp_enqueue_style( 'foldestheme-verseny', get_template_directory_uri() . '/css/verseny.css' );
    wp_enqueue_style( 'foldestheme-tablo', get_template_directory_uri() . '/css/tablo.css' );
    wp_enqueue_style( 'foldestheme-pagination', get_template_directory_uri() . '/css/pagination.css' );
    wp_enqueue_style( 'foldestheme-lowernav', get_template_directory_uri() . '/css/lowernav.css' );


    



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

function enqueue_teacher_grid_styles() {
    if (is_page_template('page-tanaraink.php')) {
        wp_enqueue_style(
            'teacher-grid',
            get_template_directory_uri() . '/css/teacher-grid.css',
            array(),
            filemtime(get_template_directory() . '/css/teacher-grid.css')
        );
    }
}
add_action('wp_enqueue_scripts', 'enqueue_teacher_grid_styles');

// JavaScript fallback in footer
add_action('wp_footer', function() {
    if (!is_user_logged_in() && is_front_page()) {
        echo <<<HTML
        <script>
        if (localStorage.getItem('initialRedirect') === 'pending') {
            localStorage.removeItem('initialRedirect');
        } else if (!sessionStorage.getItem('sessionActive')) {
            sessionStorage.setItem('sessionActive', 'true');
            window.location.href = '/otthon';
        }
        </script>
        HTML;
    }
});

function enqueue_lightbox_assets() {
    wp_enqueue_style( 'lightbox-css', get_template_directory_uri() . '/path-to/lightbox.css' );
    wp_enqueue_script( 'lightbox-js', get_template_directory_uri() . '/path-to/lightbox.js', array('jquery'), null, true );
}
add_action( 'wp_enqueue_scripts', 'enqueue_lightbox_assets' );

function enqueue_fancybox_assets() {
    // Enqueue Fancybox CSS from a CDN.
    wp_enqueue_style(
        'fancybox-css',
        'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css',
        array(),
        '3.5.7'
    );

    // Enqueue Fancybox JS from a CDN.
    wp_enqueue_script(
        'fancybox-js',
        'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js',
        array( 'jquery' ),
        '3.5.7',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'enqueue_fancybox_assets' );

function add_fancybox_to_images( $content ) {
    // This pattern matches images that are not already wrapped in a link.
    // It looks for an <img> tag that is optionally wrapped in a <p> tag.
    $pattern = '/(<p>\s*)?(<img [^>]+src="([^"]+)"[^>]*>)(\s*<\/p>)?/i';

    // The replacement wraps the image in an anchor linking to the image URL.
    $replacement = '<a data-fancybox="gallery" href="$3">$2</a>';
    
    $content = preg_replace( $pattern, $replacement, $content );
    return $content;
}
add_filter( 'the_content', 'add_fancybox_to_images' );

function add_fancybox_to_linked_images( $content ) {
    // This pattern finds <a> tags that link to an image file.
    $pattern = '/<a(?![^>]*data-fancybox)([^>]+href="([^"]+\.(?:jpg|jpeg|png|gif))"[^>]*)>/i';
    $replacement = '<a data-fancybox="gallery"$1>';
    $content = preg_replace( $pattern, $replacement, $content );
    return $content;
}
add_filter( 'the_content', 'add_fancybox_to_linked_images' );

// Add competition entry linking
function competition_entry_links($content) {
    if(is_singular('competition_entry')) {
        $archive_link = '<div class="competition-archive-link">
            <a href="'.home_url('/versenyek/').'">← Back to all competitions</a>
        </div>';
        return $content . $archive_link;
    }
    return $content;
}
add_filter('the_content', 'competition_entry_links');

function enqueue_lower_navbar_script() {
    wp_enqueue_script('lower-navbar', get_template_directory_uri() . '/js/lower-navbar.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_lower_navbar_script');