<?php
/**
 * Template Name: Versenyek Archive
 * Template Post Type: page
 */

get_header();
?>

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/versenyek-styles.css">

<div class="tag-search">
    <form id="tag-search-form" method="GET" action="">
      <div class="search-year-row">
        <div class="input-group search-group">
          <input type="text" id="tag-search-input" name="search" placeholder=" ">
          <label for="tag-search-input">Keress rá bármire...</label>
        </div>
        <div class="year-filter">
          <div class="input-group">
            <input type="number" id="from-year" name="from_year" placeholder=" ">
            <label for="from-year">Évtől</label>
          </div>
          <div class="input-group">
            <input type="number" id="to-year" name="to_year" placeholder=" ">
            <label for="to-year">Évig</label>
          </div>
        </div>
        <div class="input-group search-group">
            <input 
                type="number" 
                id="entries-per-page" 
                name="entries_per_page" 
                placeholder=" "
                value="<?php echo htmlspecialchars($_GET['entries_per_page'] ?? '400'); ?>" 
                min="1" 
                max="5000" 
                required 
            />
            <label for="entries-per-page">Bejegyzés szám:</label>
        </div>
        <button type="submit">Keresés</button>
      </div>
    </form>
</div>

<div class="versenyek-container">
    <h1 class="versenyek-title">Versenyek</h1>

    <?php
    function get_position_weight($helyezes) {
        $level_weights = [
            'nemzetközi' => 7000000,
            'országos' => 6000000,
            'regionális' => 5000000,
            'megyei' => 4000000,
            'városi' => 3000000,
            'iskolai' => 2000000
        ];

        $weight = 0;
        $helyezes = mb_strtolower($helyezes);

        if (empty($helyezes)) {
            return 0;
        }

        foreach ($level_weights as $level => $base_weight) {
            if (mb_strpos($helyezes, $level) !== false) {
                $weight = $base_weight;
                break;
            }
        }

        if (preg_match('/(\d+)/', $helyezes, $matches)) {
            $weight -= intval($matches[1]);
        } else {
            if (mb_strpos($helyezes, 'döntős') !== false) {
                $weight -= 10;
            } else if (mb_strpos($helyezes, 'továbbjutott') !== false) {
                $weight -= 9000;
            }
        }

        return $weight;
    }

    $search_term = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';
    $from_year = isset($_GET['from_year']) ? intval($_GET['from_year']) : 2000;
    $to_year = isset($_GET['to_year']) ? intval($_GET['to_year']) : 2100;
    $sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'targy';
    $order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'asc';
    $entries_per_page = isset($_GET['entries_per_page']) ? intval($_GET['entries_per_page']) : 400;
    $entries_per_page = min(max($entries_per_page, 1), 1600); // Clamp between 1 and 1600

    $paged = max(1, get_query_var('paged', isset($_GET['paged']) ? intval($_GET['paged']) : 1));
    $offset = ($paged - 1) * $entries_per_page;

    $args = array(
        'post_type' => 'competition_entry',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    );

    $query = new WP_Query($args);
    $entries = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) : $query->the_post();
            // Build the entry array, including post content for inline display.
            $entry = array(
                'post_id' => get_the_ID(),
                'targy' => '',
                'diak' => '',
                'verseny' => '',
                'osztaly' => '',
                'tanev' => '',
                'start_year' => null,
                'szint' => '',
                'tanar' => [],
                'legjobb_helyezes' => '',
                'helyezes_weight' => 0,
                'content' => get_the_content(),
                'has_thumbnail' => has_post_thumbnail()
            );

            $match_found = false;
            $year_match = true;  // Default to true

            $tags = get_the_terms(get_the_ID(), 'competition_tags');
            if ($tags && !is_wp_error($tags)) {
                foreach ($tags as $tag) {
                    $parts = explode(': ', $tag->name, 2);
                    if (count($parts) === 2) {
                        switch ($parts[0]) {
                            case 'Tárgy': 
                                $entry['targy'] = $parts[1]; 
                                break;
                            case 'Diák': 
                                $entry['diak'] = $parts[1]; 
                                break;
                            case 'Verseny': 
                                $entry['verseny'] = $parts[1]; 
                                break;
                            case 'Osztály': 
                                $entry['osztaly'] = $parts[1]; 
                                break;
                            case 'Tanév': 
                                $entry['tanev'] = $parts[1];
                                $years = explode('-', $parts[1]);
                                $entry['start_year'] = !empty($years[0]) ? intval($years[0]) : null;
                                break;
                            case 'Legjobb helyezés': 
                                $entry['legjobb_helyezes'] = $parts[1];
                                $entry['helyezes_weight'] = get_position_weight($parts[1]);
                                break;
                            case 'Tanár': 
                                $entry['tanar'][] = $parts[1]; 
                                break;
                        }

                        if (stripos($tag->name, $search_term) !== false) {
                            $match_found = true;
                        }
                    }
                }
            }

            // Apply year filtering logic:
            if (!empty($entry['start_year'])) {
                if ($from_year && $entry['start_year'] < $from_year) {
                    $year_match = false;
                }
                if ($to_year && $entry['start_year'] > $to_year) {
                    $year_match = false;
                }
            }

            if (($match_found || empty($search_term)) && $year_match) {
                $entries[] = $entry;
            }
        endwhile;

        usort($entries, function($a, $b) use ($sort, $order) {
            if ($sort === 'legjobb_helyezes') {
                $cmp = $b['helyezes_weight'] - $a['helyezes_weight'];
                return ($order === 'asc') ? -$cmp : $cmp;
            } elseif ($sort === 'tanar') {
                // Convert teacher arrays to strings before comparison
                $aTeacher = is_array($a['tanar']) ? implode(', ', $a['tanar']) : $a['tanar'];
                $bTeacher = is_array($b['tanar']) ? implode(', ', $b['tanar']) : $b['tanar'];
                $cmp = strnatcasecmp($aTeacher, $bTeacher);
                return ($order === 'asc') ? $cmp : -$cmp;
            } else {
                $cmp = strnatcasecmp($a[$sort], $b[$sort]);
                return ($order === 'asc') ? $cmp : -$cmp;
            }
        });
    }

    $total_entries = count($entries);
    $paged_entries = array_slice($entries, $offset, $entries_per_page);
    ?>

    <?php if (!empty($paged_entries)) : ?>
        <table class="versenyek-table">
            <thead>
                <tr>
                    <th class="has-image-column">Kép</th>
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
                <?php foreach ($paged_entries as $entry) : ?>
                    <!-- Main row (clickable) -->
                    <tr class="versenyek-row clickable <?php echo $entry['has_thumbnail'] ? 'has-thumbnail' : ''; ?>">
                        <td class="image-indicator">
                            <?php if ($entry['has_thumbnail']) : ?>
                                <div class="image-badge" title="Kattints a kép megtekintéséhez">
                                    <span class="image-icon"></span>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><?php echo esc_html($entry['targy']); ?></td>
                        <td><?php echo esc_html($entry['diak']); ?></td>
                        <td><?php echo esc_html($entry['verseny']); ?></td>
                        <td><?php echo esc_html($entry['osztaly']); ?></td>
                        <td><?php echo esc_html($entry['tanev']); ?></td>
                        <td><?php echo esc_html($entry['legjobb_helyezes']); ?></td>
                        <td><?php echo esc_html(implode(', ', $entry['tanar'])); ?></td>
                    </tr>
                    <!-- Hidden details row -->
                    <tr class="entry-details" style="display: none;">
                        <td colspan="8">
                            <?php 
                            // Display thumbnail if exists
                            if ($entry['has_thumbnail']) {
                                $post_id = $entry['post_id'];
                                echo '<div class="entry-thumbnail">';
                                echo get_the_post_thumbnail($post_id, 'large');
                                echo '</div>';
                            }
                            
                            // Apply content filters so shortcodes, etc. work
                            echo apply_filters('the_content', $entry['content']); 
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php
            $base_url = remove_query_arg('paged', $_SERVER['REQUEST_URI']);
            if ($paged > 1) {
                echo '<a href="'.esc_url(add_query_arg('paged', $paged - 1, $base_url)).'">Előző oldal</a>';
            }
            if ($offset + count($paged_entries) < $total_entries) {
                echo '<a href="'.esc_url(add_query_arg('paged', $paged + 1, $base_url)).'">Következő oldal</a>';
            }
            ?>
        </div>

    <?php else : ?>
        <p class="versenyek-no-results">Nincsenek verseny bejegyzések.</p>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sorting functionality
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

    // Updated toggle function for the hidden details row
    document.querySelectorAll('.versenyek-row.clickable').forEach(row => {
        row.style.cursor = 'pointer';
        row.addEventListener('click', function() {
            const detailsRow = this.nextElementSibling;
            
            // First remove active class from all rows
            document.querySelectorAll('.versenyek-row.active').forEach(activeRow => {
                activeRow.classList.remove('active');
            });
            
            document.querySelectorAll('.entry-details.active').forEach(activeDetails => {
                activeDetails.classList.remove('active');
            });
            
            if (detailsRow && detailsRow.classList.contains('entry-details')) {
                if (detailsRow.style.display === 'none') {
                    // Close any open details first
                    document.querySelectorAll('.entry-details').forEach(details => {
                        details.style.display = 'none';
                    });
                    
                    // Then open this one and add active classes
                    detailsRow.style.display = 'table-row';
                    this.classList.add('active');
                    detailsRow.classList.add('active');
                } else {
                    // Just close this one
                    detailsRow.style.display = 'none';
                }
            }
        });
    });
});
</script>

<style>
/* Styles for thumbnail indicators and display */
.has-image-column {
    width: 40px;
    text-align: center;
}

.image-indicator {
    vertical-align: middle;
    text-align: center;
}

.image-badge {
    display: inline-block;
    background-color: #0073aa;
    color: white;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    line-height: 30px;
    text-align: center;
    animation: pulse 2s infinite;
    box-shadow: 0 0 0 rgba(0, 115, 170, 0.4);
}

.image-icon {
    font-size: 16px;
}

.versenyek-row.has-thumbnail {
    background-color: rgba(0, 115, 170, 0.05);
}

.versenyek-row.has-thumbnail:hover {
    background-color: rgba(0, 115, 170, 0.1);
}

.entry-thumbnail {
    margin: 20px auto;
    text-align: center;
    max-width: 90%;
}

.entry-thumbnail img {
    max-width: 50%;
    height: auto;
    border: 2px solid #0073aa;
    border-radius: 6px;
    padding: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(0, 115, 170, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(0, 115, 170, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(0, 115, 170, 0);
    }
}

/* Dark effect for opened entries */
tr.entry-details.active {
    background-color: rgba(0, 0, 0, 0.03);
    box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Style for the row that has been clicked to open details */
tr.versenyek-row.clickable.active {
    background-color: rgba(0, 0, 0, 0.08);
    font-weight: 600;
}

/* Specific styling for rows with thumbnails that are active */
tr.versenyek-row.has-thumbnail.active {
    background-color: rgba(0, 115, 170, 0.2);
}
</style>

<?php
wp_reset_postdata();
get_footer();
?>