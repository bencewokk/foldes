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
      <!-- Single Row Container for Search Input, Year Filters, and Button -->
      <div class="search-year-row">
        <!-- Search Input -->
        <div class="input-group search-group">
          <input type="text" id="tag-search-input" name="search" placeholder=" ">
          <label for="tag-search-input">Keresés címkékben</label>
        </div>
        <!-- Year Filters -->
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
        <!-- Submit Button -->
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
    $from_year = isset($_GET['from_year']) ? intval($_GET['from_year']) : null;
    $to_year = isset($_GET['to_year']) ? intval($_GET['to_year']) : null;
    $sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'targy';
    $order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'asc';

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
                'start_year' => null,
                'szint' => '',
                'tanar' => '',
                'legjobb_helyezes' => '',
                'helyezes_weight' => 0
            );

            $match_found = false;
            $year_match = true;

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
                            case 'Tanév': 
                                $entry['tanev'] = $parts[1];
                                $years = explode('-', $parts[1]);
                                $entry['start_year'] = !empty($years[0]) ? intval($years[0]) : null;
                                break;
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

            // Year filtering logic
            if ($from_year || $to_year) {
                $year_match = false;
                if ($entry['start_year']) {
                    $year = $entry['start_year'];
                    if ($from_year && $to_year) {
                        $year_match = ($year >= $from_year && $year <= $to_year);
                    } elseif ($from_year) {
                        $year_match = ($year >= $from_year);
                    } elseif ($to_year) {
                        $year_match = ($year <= $to_year);
                    }
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
?>