<?php
require_once('wp-load.php');
function upload_csv_to_wp($csv_file_path) {
    if (!file_exists($csv_file_path) || !is_readable($csv_file_path)) {
        echo "CSV file not found or unreadable.";
        return;
    }

    $file_handle = fopen($csv_file_path, 'r');
    $headers = fgetcsv($file_handle); // Skip header row

    while (($row = fgetcsv($file_handle)) !== false) {
        // Check if the row has the correct number of columns
        if (count($row) < 15) {
            echo "Skipping row with missing columns: " . implode(', ', $row) . "\n";
            continue; // Skip rows with insufficient data
        }

        // Map CSV columns to variables
        list(
            $targy, $verseny, $tipus, $iskolai, $varosi, $megyei, 
            $regionalis, $oszagos, $nemzetkozi, $donto, $tanarok, 
            $diak, $osztaly, $megjegyzes, $tanev
        ) = $row;

        // Collect helyezés tags (Only add if there is content)
        $helyezes_tags = [];
        if (!empty($iskolai) && $iskolai != 'tovább') $helyezes_tags[] = 'Iskolai';
        if (!empty($varosi) && $varosi != 'tovább') $helyezes_tags[] = 'Varosi';
        if (!empty($megyei) && $megyei != 'tovább') $helyezes_tags[] = 'Megyei';
        if (!empty($regionalis) && $regionalis != 'tovább') $helyezes_tags[] = 'Regionalis';
        if (!empty($oszagos) && $oszagos != 'tovább') $helyezes_tags[] = 'Oszagos';
        if (!empty($nemzetkozi) && $nemzetkozi != 'tovább') $helyezes_tags[] = 'Nemzetkozi';

        // Add döntő tag if exists and not empty
        $donto_tag = ($donto !== '' && $donto != 'tovább') ? "Donto$donto" : '';

        // Combine tags
        $helyezes_tag_combined = implode('', $helyezes_tags);
        if ($tipus) $helyezes_tag_combined .= " ($tipus)";
        if ($donto_tag) $helyezes_tag_combined .= " $donto_tag";

        // Create post content
        $post_title = "$diak - $verseny";
        
        // Build post content with fallbacks if variables are empty
        $post_content = "$diak ($osztaly) részt vett a $verseny versenyen";
        if ($tipus) $post_content .= " ($tipus)";
        $post_content .= ", vezető tanár: $tanarok a $tanev tanévben. Megjegyzések: $megjegyzes.";

        // Create WordPress post
        $post_id = wp_insert_post([
            'post_title'   => $post_title,
            'post_content' => $post_content,
            'post_status'  => 'publish',
            'post_type'    => 'post',
            'post_author'  => 1
        ]);

        if ($post_id) {
            // Prepare tags array, ensure we have relevant tags
            $tag_array = array_filter(array_merge(
                array_map('trim', [$targy, $verseny, $tipus, $tanarok, $tanev, $diak, $osztaly]),
                [$helyezes_tag_combined],
                $donto_tag ? [$donto_tag] : []
            ));

            // Ensure tags are always added
            if (!empty($tag_array)) {
                wp_set_post_tags($post_id, $tag_array);
            } else {
                wp_set_post_tags($post_id, 'No Tags');
            }

            echo "Post created: $post_id ($post_title)\n";
        } else {
            echo "Error creating post for: $diak\n";
        }
    }

    fclose($file_handle);
}




// Set your CSV path
$csv_file_path = 'C:\Users\bence\Desktop\foldes\filteredtest.xlsx';
upload_csv_to_wp($csv_file_path);