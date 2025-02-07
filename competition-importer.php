<?php
/*
Plugin Name: Competition Loader with Tags
Description: Handles competition data loading with proper tagging
Version: 1.6
*/

// Main initialization
function competition_loader_init() {
    register_competition_components();
    add_import_endpoint();
    add_action('template_redirect', 'handle_csv_import');
}
add_action('init', 'competition_loader_init');

// Register components
function register_competition_components() {
    // Post Type
    register_post_type('competition_entry', [
        'labels' => [
            'name' => __('Competition Entries'),
            'singular_name' => __('Competition Entry'),
        ],
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'competitions'],
        'supports' => ['title', 'editor', 'custom-fields'],
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-awards',
    ]);

    // Taxonomy
    register_taxonomy('competition_tags', 'competition_entry', [
        'label' => __('Competition Tags'),
        'rewrite' => ['slug' => 'competition-tag'],
        'hierarchical' => false,
        'show_admin_column' => true,
        'show_ui' => true,
    ]);
}

// Endpoint setup
function add_import_endpoint() {
    add_rewrite_endpoint('loadverseny', EP_ROOT);
}

// Import handler with tag debugging
function handle_csv_import() {
    global $wp_query;
    
    if (!isset($wp_query->query_vars['loadverseny'])) return;

    ob_start();
    echo "<h1>Competition Data Loader</h1><pre>\n";
    
    if (!current_user_can('manage_options')) {
        wp_die('🚫 Access denied! Administrator required.');
    }

    $csv_path = 'E:\letoltes\wordpress\filteredtest.csv';
    echo "📂 File: " . esc_html($csv_path) . "\n\n";

    if (!file_exists($csv_path)) {
        wp_die("❌ File not found!");
    }

    try {
        $handle = fopen($csv_path, "r");
        fgetcsv($handle); // Skip header
        
        $count = 0;
        while ($data = fgetcsv($handle)) {
            echo "\n--- Processing Row $count ---\n";
            
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
            ], true);

            if (is_wp_error($post_id)) {
                echo "❌ Post Error: " . esc_html($post_id->get_error_message()) . "\n";
                continue;
            }

            echo "✅ Post ID: $post_id\n";
            
            // TAG GENERATION
            $tags = [];
            
            // Basic tags
            $tags[] = 'Tárgy: ' . $data[0];
            $tags[] = 'Verseny: ' . $data[1];
            $tags[] = 'Típus: ' . $data[2];
            $tags[] = 'Tanév: ' . $data[14];
            
            // Level tags
            $levels = [
                3 => 'Iskolai',
                4 => 'Városi',
                5 => 'Megyei',
                6 => 'Regionális',
                7 => 'Országos',
                8 => 'Nemzetközi'
            ];
            
            foreach ($levels as $index => $label) {
                if (!empty($data[$index])) {
                    $tags[] = $label . ' Szint: ' . $data[$index];
                }
            }
            
            // Teacher tags
            $teachers = array_map('trim', explode(',', $data[10]));
            foreach ($teachers as $teacher) {
                if (!empty($teacher)) {
                    $tags[] = 'Tanár: ' . $teacher;
                }
            }
            
            // Class tag
            $tags[] = 'Osztály: ' . $data[12];
            
            // Diák tag
            $tags[] = 'Diák: ' . $data[11]; // Add Diák tag
            
            // Debug tags
            echo "🏷️ Tags to add:\n";
            print_r($tags);
            
            // Insert tags
            $result = wp_set_post_terms($post_id, $tags, 'competition_tags', true);
            
            if (is_wp_error($result)) {
                echo "❌ Tag Error: " . esc_html($result->get_error_message()) . "\n";
            } else {
                echo "✅ Tags added: " . count($tags) . "\n";
            }
            
            $count++;
        }
        
        echo "\n✨ Complete! Processed $count entries.";
        
    } finally {
        fclose($handle);
        $output = ob_get_clean();
        wp_die($output, 'Import Results');
    }
}

// Activation
function competition_loader_activate() {
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'competition_loader_activate');