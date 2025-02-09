<?php
/**
 * Template Name: Versenyek Archive
 * Template Post Type: page
 */

get_header();
?>

<!-- Include the custom CSS -->
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/versenyek-styles.css">

<div class="tag-search">
    <form id="tag-search-form" method="GET" action="">
        <div class="input-group">
            <input type="text" id="tag-search-input" name="search" placeholder="Search in tags..." value="<?php echo isset($_GET['search']) ? esc_attr($_GET['search']) : ''; ?>">
            <label for="tag-search-input">Keress rá bármire...</label>
        </div>
        <button type="submit">Search</button>
    </form>
</div>

<div class="versenyek-container">
    <h1 class="versenyek-title">Versenyek</h1>

    <?php
    // Helper function to get position weight
    function get_position_weight($helyezes) {
        // Define level weights (higher = more important)
        $level_weights = [
            'nemzetközi' => 2000000,
            'országos' => 3000000,
            'regionális' => 4000000,
            'megyei' => 5000000,
            'városi' => 6000000,
            'iskolai' => 7000000
        ];

        // Initialize weight
        $weight = 0;
        $helyezes = mb_strtolower($helyezes); // Convert to lowercase for comparison

        // Check for empty value
        if (empty($helyezes)) {
            return 0;
        }

        // Check each level
        foreach ($level_weights as $level => $base_weight) {
            if (mb_strpos($helyezes, $level) !== false) {
                $weight = $base_weight;
                break;
            }
        }

        // Extract numeric position if exists
        if (preg_match('/(\d+)/', $helyezes, $matches)) {
            // Subtract position to sort within same level (lower number = higher rank)
            // Using 10000 as buffer to handle positions up to 9999
            $weight -= intval($matches[1]);
        } else {
            // Handle special cases
            if (mb_strpos($helyezes, 'döntős') !== false) {
                $weight -= 10; // Place after numbered positions
            } else if (mb_strpos($helyezes, 'továbbjutott') !== false) {
                $weight -= 9000; // Place near end of level
            }
        }

        return $weight;
    }

    // Get search term from URL
    $search_term = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';

    // Get sorting parameters from URL
    $sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'targy';
    $order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'asc';

    // Get all competition entries
    $args = array(
        'post_type' => 'competition_entry',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    );

    $query = new WP_Query($args);
    $entries = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) : $query->the_post();
            $entry = array(
                'post_id' => get_the_ID(),
                'targy' => '',
                'diak' => '',
                'verseny' => '',
                'osztaly' => '',
                'tanev' => '',
                'szint' => '',
                'tanar' => '',
                'legjobb_helyezes' => '',
                'helyezes_weight' => 0  // New field for sorting
            );

            $match_found = false;

            // Parse tags
            $tags = get_the_terms(get_the_ID(), 'competition_tags');
            if ($tags && !is_wp_error($tags)) {
                foreach ($tags as $tag) {
                    $parts = explode(': ', $tag->name, 2);
                    if (count($parts) === 2) {
                        switch ($parts[0]) {
                            case 'Tárgy': $entry['targy'] = $parts[1]; break;
                            case 'Diák': $entry['diak'] = $parts[1]; break;
                            case 'Verseny': $entry['verseny'] = $parts[1]; break;
                            case 'Osztály': $entry['osztaly'] = $parts[1]; break;
                            case 'Tanév': $entry['tanev'] = $parts[1]; break;
                            case 'Legjobb helyezés': 
                                $entry['legjobb_helyezes'] = $parts[1];
                                $entry['helyezes_weight'] = get_position_weight($parts[1]);
                                break;
                            case 'Megyei Szint':
                            case 'Országos Szint':
                            case 'Nemzetközi Szint': 
                                $entry['szint'] = $parts[1]; 
                                break;
                            case 'Tanár': $entry['tanar'] = $parts[1]; break;
                        }

                        if (stripos($tag->name, $search_term) !== false) {
                            $match_found = true;
                        }
                    }
                }
            }

            if ($match_found || empty($search_term)) {
                $entries[] = $entry;
            }
        endwhile;
        
        // Sort entries
        usort($entries, function($a, $b) use ($sort, $order) {
            if ($sort === 'legjobb_helyezes') {
                // Special sorting for positions
                $cmp = $b['helyezes_weight'] - $a['helyezes_weight']; // Higher weight first
                return ($order === 'asc') ? -$cmp : $cmp;
            } else {
                $cmp = strnatcasecmp($a[$sort], $b[$sort]);
                return ($order === 'asc') ? $cmp : -$cmp;
            }
        });
    }
    ?>

    <?php if (!empty($entries)) : ?>
        <table class="versenyek-table">
            <thead>
                <tr>
                    <?php
                    $columns = array(
                        'targy' => 'Tárgy',
                        'diak' => 'Diák',
                        'verseny' => 'Verseny',
                        'osztaly' => 'Osztály',
                        'tanev' => 'Tanév',
                        'legjobb_helyezes' => 'Legjobb helyezés',
                        'tanar' => 'Tanár'
                    );
                    
                    foreach ($columns as $key => $label) {
                        $new_order = ($sort === $key && $order === 'asc') ? 'desc' : 'asc';
                        $arrow = ($sort === $key) ? ($order === 'asc' ? '↑' : '↓') : '';
                        echo '<th class="sortable" 
                                  data-sort="'.esc_attr($key).'" 
                                  data-order="'.esc_attr($new_order).'">
                            '.esc_html($label).' '.$arrow.'
                        </th>';
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($entries as $entry) : ?>
                    <tr class="versenyek-row">
                        <td><?php echo esc_html($entry['targy']); ?></td>
                        <td><?php echo esc_html($entry['diak']); ?></td>
                        <td><?php echo esc_html($entry['verseny']); ?></td>
                        <td><?php echo esc_html($entry['osztaly']); ?></td>
                        <td><?php echo esc_html($entry['tanev']); ?></td>
                        <td><?php echo esc_html($entry['legjobb_helyezes']); ?></td>
                        <td><?php echo esc_html($entry['tanar']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p class="versenyek-no-results">Nincsenek verseny bejegyzések.</p>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.sortable').forEach(header => {
        header.addEventListener('click', function() {
            const sortField = this.dataset.sort;
            const newOrder = this.dataset.order;
            
            const url = new URL(window.location.href);
            url.searchParams.set('sort', sortField);
            url.searchParams.set('order', newOrder);
            
            window.location.href = url.toString();
        });
    });
});
</script>

<?php
wp_reset_postdata();
get_footer();