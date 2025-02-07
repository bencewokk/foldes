<?php
/*
Plugin Name: Competition Loader
Description: Handles competition data loading at /loadverseny
Version: 1.4
*/

// Main initialization
function competition_loader_init() {
    register_competition_components();
    add_import_endpoint();
    add_action('template_redirect', 'handle_csv_import');
}
add_action('init', 'competition_loader_init');

// Register custom post type and taxonomy
function register_competition_components() {
    // Competition Entry post type
    register_post_type('competition_entry', [
        'labels' => [
            'name' => __('Competition Entries'),
            'singular_name' => __('Competition Entry'),
        ],
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'competitions'],
        'supports' => ['title', 'editor', 'custom-fields'],
    ]);

    // Competition Tags taxonomy
    register_taxonomy('competition_tags', 'competition_entry', [
        'label' => __('Competition Tags'),
        'rewrite' => ['slug' => 'competition-tag'],
        'hierarchical' => false,
        'show_admin_column' => true,
    ]);
}

// Add custom rewrite endpoint
function add_import_endpoint() {
    add_rewrite_endpoint('loadverseny', EP_ROOT);
}

// CSV import handler
function handle_csv_import() {
    global $wp_query;
    
    // Check for our specific endpoint
    if (!isset($wp_query->query_vars['loadverseny'])) {
        return;
    }

    // Start output
    ob_start();
    echo "<h1>Competition Data Loader</h1><pre>\n";

    // Authorization check
    if (!current_user_can('manage_options')) {
        wp_die('🚫 Access denied! Administrator required.');
    }

    // File path - adjust if needed
    $csv_path = 'E:\letoltes\wordpress\filteredtest.csv';
    echo "📂 Loading: " . esc_html($csv_path) . "\n\n";

    if (!file_exists($csv_path)) {
        wp_die("❌ Error: File not found!");
    }

    // Process CSV
    try {
        $handle = fopen($csv_path, "r");
        fgetcsv($handle); // Skip header
        
        $count = 0;
        while ($data = fgetcsv($handle)) {
            echo "Processing: " . esc_html($data[0]) . "... ";
            
            // Create post
            $post_id = wp_insert_post([
                'post_title'   => sanitize_text_field($data[0] . ' - ' . $data[11]),
                'post_content' => sprintf(
                    "Tanár: %s\nDiák: %s\nOsztály: %s\nMegjegyzés: %s",
                    sanitize_text_field($data[10]),
                    sanitize_text_field($data[11]),
                    sanitize_text_field($data[12]),
                    sanitize_text_field($data[13])
                ),
                'post_type'    => 'competition_entry',
                'post_status'  => 'publish',
            ]);

            if (!is_wp_error($post_id)) {
                echo "✅ Created ID: $post_id\n";
                $count++;
            } else {
                echo "❌ Error: " . esc_html($post_id->get_error_message()) . "\n";
            }
        }
        
        echo "\n✨ Complete! Imported $count entries.";
        
    } finally {
        fclose($handle);
        $output = ob_get_clean();
        wp_die($output, 'Import Results');
    }
}

// Activation setup
function competition_loader_activate() {
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'competition_loader_activate');

function competition_loader_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'competition_loader_deactivate');