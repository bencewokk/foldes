<?php
/*
Plugin Name: Competition Loader with Manual Entry
Description: Handles competition data with CSV upload and manual entry
Version: 2.0
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
        'supports' => ['title', 'editor', 'custom-fields', 'thumbnail'], // Added thumbnail support here
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

// Admin menu setup
function competition_loader_admin_menu() {
    add_submenu_page(
        'edit.php?post_type=competition_entry',
        'Import Competitions',
        'Add Competitions',
        'edit_posts',
        'competition-import',
        'competition_import_page'
    );
}
add_action('admin_menu', 'competition_loader_admin_menu');

// Admin page with tabs
function competition_import_page() {
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'manual_entry'; ?>
    <div class="wrap">
        <h1>Add Competition Entries</h1>
        <h2 class="nav-tab-wrapper">
            <a href="?post_type=competition_entry&page=competition-import&tab=manual_entry" class="nav-tab <?php echo $active_tab === 'manual_entry' ? 'nav-tab-active' : ''; ?>">Manual Entry</a>
            <a href="?post_type=competition_entry&page=competition-import&tab=csv_import" class="nav-tab <?php echo $active_tab === 'csv_import' ? 'nav-tab-active' : ''; ?>">CSV Import</a>
        </h2>

        <?php
        if ($active_tab === 'manual_entry') {
            show_manual_entry_form();
        } else {
            show_csv_upload_form();
        }
        ?>
    </div>
    <?php
}

// Manual entry form
function show_manual_entry_form() {
    if (isset($_POST['submit_manual'])) {
        handle_manual_entry();
    } ?>
    <form method="post">
        <?php wp_nonce_field('competition_manual_entry', '_wpnonce'); ?>
        <table class="form-table">
            <tr>
                <th scope="row">Tantargy</th>
                <td><input type="text" name="targy" required class="regular-text"></td>
            </tr>
            <tr>
                <th>Verseny neve</th>
                <td><input type="text" name="verseny" required class="regular-text"></td>
            </tr>
            <tr>
                <th>Tipus (Egyeni - Csapat)</th>
                <td><input type="text" name="tipus" required class="regular-text"></td>
            </tr>
            <tr>
                <th>Tanév</th>
                <td><input type="text" name="tanev" required class="regular-text"></td>
            </tr>
            <tr>
                <th>Elert eredmenyek</th>
                <td>
                    <?php
                    $levels = [
                        'Iskolai' => 'School Level',
                        'Városi' => 'City Level',
                        'Megyei' => 'County Level',
                        'Regionális' => 'Regional Level',
                        'Országos' => 'National Level',
                        'Nemzetközi' => 'International Level'
                    ];
                    
                    foreach ($levels as $key => $label) {
                        echo '<div class="level-input">';
                        echo '<label>' . esc_html($label) . ':';
                        echo '<input type="text" name="' . strtolower($key) . '" class="small-text">';
                        echo '</label></div>';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th>Tanárok, VESSZŐVEL elvalasztva; Tóth Árpád, Ekecsi Krisztina</th>
                <td><input type="text" name="tanar" required class="regular-text"></td>
            </tr>
            <tr>
                <th>Diák</th>
                <td><input type="text" name="diak" required class="regular-text"></td>
            </tr>
            <tr>
                <th>Osztály</th>
                <td><input type="text" name="osztaly" required class="regular-text"></td>
            </tr>
            <tr>
                <th>Megjegyzés</th>
                <td><textarea name="megjegyzes" class="large-text"></textarea></td>
            </tr>
        </table>
        <?php submit_button('Add Competition Entry', 'primary', 'submit_manual'); ?>
    </form>
    <?php
}

// Handle manual entry submission
function handle_manual_entry() {
    if (!wp_verify_nonce($_POST['_wpnonce'], 'competition_manual_entry')) {
        wp_die('Security check failed');
    }

    $allowed_roles = get_option('competition_loader_allowed_roles', ['administrator', 'editor']);
    $user = wp_get_current_user();
    if (!array_intersect($allowed_roles, $user->roles)) {
        wp_die('Permission denied');
    }

    // Create post
    $post_id = wp_insert_post([
        'post_title'   => sanitize_text_field($_POST['targy'] . ' - ' . $_POST['diak']),
        'post_content' => sprintf(
            "Tanár: %s\nDiák: %s\nOsztály: %s\nMegjegyzés: %s",
            sanitize_text_field($_POST['tanar']),
            sanitize_text_field($_POST['diak']),
            sanitize_text_field($_POST['osztaly']),
            sanitize_textarea_field($_POST['megjegyzes'])
        ),
        'post_type'    => 'competition_entry',
        'post_status'  => 'publish',
    ]);

    if (is_wp_error($post_id)) {
        echo '<div class="notice notice-error"><p>Error creating entry: ' . esc_html($post_id->get_error_message()) . '</p></div>';
        return;
    }

    // Generate tags
    $tags = [
        'Tárgy: ' . $_POST['targy'],
        'Verseny: ' . $_POST['verseny'],
        'Típus: ' . $_POST['tipus'],
        'Tanév: ' . $_POST['tanev'],
        'Tanár: ' . $_POST['tanar'],
        'Diák: ' . $_POST['diak'],
        'Osztály: ' . $_POST['osztaly']
    ];

    // Add level tags
    $level_values = [
        'iskolai' => 'Iskolai',
        'városi' => 'Városi',
        'megyei' => 'Megyei',
        'regionális' => 'Regionális',
        'országos' => 'Országos',
        'nemzetközi' => 'Nemzetközi'
    ];

    $best_position = null;
    $best_level = null;
    
    foreach ($level_values as $key => $label) {
        if (!empty($_POST[$key])) {
            $value = sanitize_text_field($_POST[$key]);
            $tags[] = $label . ' Szint: ' . $value;
            
            // Best position logic (same as CSV version)
            $lower_val = strtolower($value);
            if (preg_match('/(\d+)/', $value, $matches)) {
                $current_position = intval($matches[1]);
                if ($best_position === null || $current_position < $best_position) {
                    $best_position = $current_position;
                    $best_level = $label;
                }
            }
        }
    }

    // Add best position tag
    if ($best_position !== null) {
        $tags[] = 'Legjobb helyezés: ' . $best_level . ' ' . $best_position . '.';
    }

    // Set tags
    wp_set_post_terms($post_id, $tags, 'competition_tags', true);

    echo '<div class="notice notice-success"><p>Entry added successfully! ID: ' . $post_id . '</p></div>';
}

// CSV upload form
function show_csv_upload_form() {
    if (isset($_POST['submit_csv'])) {
        competition_handle_csv_upload();
    } ?>
    <form method="post" enctype="multipart/form-data">
        <?php wp_nonce_field('competition_csv_import', '_wpnonce'); ?>
        <p>
            <input type="file" name="competition_csv[]" accept=".csv" multiple>
            <?php submit_button('Upload and Import', 'primary', 'submit_csv'); ?>
        </p>
    </form>
    <?php
}

// Handle CSV upload and processing
function competition_handle_csv_upload() {
    if (!wp_verify_nonce($_POST['_wpnonce'], 'competition_csv_import')) {
        wp_die('Security check failed');
    }

    $allowed_roles = get_option('competition_loader_allowed_roles', ['administrator', 'editor', 'author']);
    $user = wp_get_current_user();
    if (!array_intersect($allowed_roles, $user->roles)) {
        wp_die('Permission denied');
    }

    if (empty($_FILES['competition_csv']['tmp_name'])) {
        echo '<div class="notice notice-error"><p>Please select at least one CSV file</p></div>';
        return;
    }

    $files = $_FILES['competition_csv'];
    $output = '';

    foreach ($files['tmp_name'] as $index => $tmp_name) {
        if ($files['error'][$index] !== UPLOAD_ERR_OK) {
            $output .= "❌ Error uploading file: " . esc_html($files['name'][$index]) . "\n";
            continue;
        }

        $output .= "📂 Processing file: " . esc_html($files['name'][$index]) . "\n";
        $output .= process_competition_csv($tmp_name);
        $output .= "\n\n";
    }

    echo '<div class="notice notice-success"><pre>' . esc_html($output) . '</pre></div>';
}

// Universal CSV processor
function process_competition_csv($csv_path) {
    ob_start();
    echo "Starting CSV Processing...\n\n";

    if (!file_exists($csv_path)) {
        return "❌ File not found!";
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
            
            // Level tags and find best position
            $levels = [
                3 => 'Iskolai',
                4 => 'Városi',
                5 => 'Megyei',
                6 => 'Regionális',
                7 => 'Országos',
                8 => 'Nemzetközi'
            ];
            
            $best_position = null;
            $best_level = null;
            $position_text = '';
            
            foreach ($levels as $index => $label) {
                if (!empty($data[$index])) {
                    $position = trim($data[$index]);
                    $tags[] = $label . ' Szint: ' . $position;
                    
                    // Special case handling
                    $lower_pos = strtolower($position);
                    
                    // Handle numeric positions
                    if (preg_match('/(\d+)/', $position, $matches)) {
                        $current_position = intval($matches[1]);
                        
                        // Check if this is an improvement over current best
                        $is_better = false;
                        if ($best_position === null) {
                            $is_better = true;
                        } else if ($label === 'Országos' || $label === 'Nemzetközi') {
                            $is_better = true; // Always prefer national/international results
                        } else if ($best_level === $label && $current_position < $best_position) {
                            $is_better = true;
                        }
                        
                        if ($is_better) {
                            $best_position = $current_position;
                            $best_level = $label;
                            $position_text = $label . ' ' . $current_position . '.';
                        }
                    }
                    // Handle special text cases
                    else if (strpos($lower_pos, 'döntő') !== false || 
                             strpos($lower_pos, 'döntőbe jutott') !== false) {
                        if ($best_position === null || $label === 'Országos' || $label === 'Nemzetközi') {
                            $position_text = $label . ' döntős';
                            $best_level = $label;
                            $best_position = 0; // To ensure it's considered better than numeric positions
                        }
                    }
                    else if (strpos($lower_pos, 'tovább') !== false || 
                             strpos($lower_pos, 'fordulóba jutott') !== false) {
                        if ($best_position === null) {
                            $position_text = $label . ' továbbjutott';
                            $best_level = $label;
                            $best_position = 999; // Lower priority than numeric positions
                        }
                    }
                }
            }
            
            // Add best position tag if found
            if ($position_text) {
                $tags[] = 'Legjobb helyezés: ' . $position_text;
            }
            
            // Teacher tags
            $teachers = array_map('trim', explode(',', $data[10]));
            foreach ($teachers as $teacher) {
                if (!empty($teacher)) {
                    $tags[] = 'Tanár: ' . $teacher;
                }
            }
            
            // Class and student tags
            $tags[] = 'Osztály: ' . $data[12];
            $tags[] = 'Diák: ' . $data[11];
            
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
        if (isset($handle)) fclose($handle);
    }

    return ob_get_clean();
}

// Endpoint handler (original functionality)
function handle_csv_import() {
    global $wp_query;
    
    if (!isset($wp_query->query_vars['loadverseny'])) return;

    if (!current_user_can('edit_posts')) {
        wp_die('🚫 Access denied! Author role required.');
    }

    $csv_path = 'C:\Users\bence\Desktop\foldes\filtered_FFG_versenyek_osszegyujtott.csv';
    $output = process_competition_csv($csv_path);
    
    wp_die($output, 'Import Results');
}

// Activation
function competition_loader_activate() {
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'competition_loader_activate');