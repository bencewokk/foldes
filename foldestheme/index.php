<?php
/**
 * Template Name: Otthon
 */
get_header();
?>

<main id="main-content">
    <div class="container">
        <!-- Tag Search and Sort Form -->
        <div class="tag-search">
            <form action="<?php echo esc_url(home_url('/')); ?>" method="get">
                <!-- Search Input -->
                <div class="input-group searchbar-group">
                    <input type="text" id="tag-search" name="tag" placeholder=" " value="<?php echo isset($_GET['tag']) ? esc_attr($_GET['tag']) : ''; ?>">
                    <label for="tag-search">Írj be egy címkét...</label>
                </div>

                <!-- Order Select -->
                <div class="input-group">
                    <select id="order" name="order">
                        <option value="DESC" <?php selected(get_query_var('order'), 'DESC'); ?>>Legújabb</option>
                        <option value="ASC" <?php selected(get_query_var('order'), 'ASC'); ?>>Legrégebbi</option>
                    </select>
                </div>

                <!-- Toggle Switch -->
                <div class="toggle-container">
                    <label class="toggle-switch-group">
                        <div class="toggle-switch-wrapper">
                            <?php
                            // Check if initial load (no parameters)
                            $is_initial_load = !isset($_GET['tag']) && !isset($_GET['order']) && !isset($_GET['fontos_toggle']);
                            ?>
                            <input type="checkbox" class="toggle-switch-input" name="fontos_toggle" value="1" 
                                <?php checked($is_initial_load || (isset($_GET['fontos_toggle']) && $_GET['fontos_toggle'] === '1')); ?>>
                            <div class="toggle-switch-track">
                                <div class="toggle-switch-thumb"></div>
                            </div>
                        </div>
                        <span class="toggle-label">Fontos hírek elől</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit">
                    Keresés és rendezés <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>

        <?php
        // Query parameters
        $order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'DESC';
        $searched_tag = isset($_GET['tag']) ? sanitize_text_field($_GET['tag']) : '';
        
        // Determine initial load state
        $is_initial_load = !isset($_GET['tag']) && !isset($_GET['order']) && !isset($_GET['fontos_toggle']);
        $fontos_toggle = $is_initial_load ? true : (isset($_GET['fontos_toggle']) && $_GET['fontos_toggle'] === '1');

        // Rest of the query code remains the same...
        // Base tax query - always require 'hír' tag
        $tax_query = array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'post_tag',
                'field'    => 'slug',
                'terms'    => array('hir'),
            )
        );


        // Add searched tags if provided
        if (!empty($searched_tag)) {
            $searched_tags = array_map('trim', explode(',', $searched_tag));
            $tax_query[] = array(
                'taxonomy' => 'post_tag',
                'field'    => 'slug',
                'terms'    => $searched_tags,
            );
        }

        // Build query args
        $query_args = array(
            'post_type'      => 'post',
            'posts_per_page' => 10,
            'order'          => $order,
            'tax_query'      => $tax_query,
        );

        // Handle fontos toggle
        if ($fontos_toggle) {
            add_filter('posts_clauses', function ($clauses) {
                global $wpdb;
                $fontos_term = get_term_by('name', 'fontos', 'post_tag');
                if ($fontos_term) {
                    $term_taxonomy_id = intval($fontos_term->term_taxonomy_id);
                    $clauses['join'] .= " LEFT JOIN {$wpdb->term_relationships} AS fontos_tr 
                        ON {$wpdb->posts}.ID = fontos_tr.object_id 
                        AND fontos_tr.term_taxonomy_id = {$term_taxonomy_id}
                        AND {$wpdb->posts}.post_date >= DATE_SUB(NOW(), INTERVAL 6 WEEK)";
                    $clauses['orderby'] = "fontos_tr.object_id IS NOT NULL DESC, " . $clauses['orderby'];
                }
                return $clauses;
            });
        }

        $custom_query = new WP_Query($query_args);
        ?>

        <?php if ($custom_query->have_posts()) : ?>
            <?php while ($custom_query->have_posts()) : $custom_query->the_post(); ?>
                <article <?php post_class('post-card'); ?>>

                  <?php if(has_tag('fontos')) : ?>
                      <div class="fontos-badge" data-tooltip="Ez azért lett előrehozva, mert fontos">Fontos</div>
                  <?php endif; ?>

                    <header class="post-header">
                        <h2 class="post-title"><?php the_title(); ?></h2>
                    </header>

                    <div class="post-content">
                        <?php the_content(); ?>
                    </div>

                    <p class="post-meta">
                        By <?php the_author(); ?> | <?php echo get_the_date(); ?>
                    </p>
                </article>
            <?php endwhile; ?>

            <div class="pagination">
                <?php
                echo paginate_links(array(
                    'total'     => $custom_query->max_num_pages,
                    'prev_text' => '<i class="fas fa-chevron-left"></i> Newer Posts',
                    'next_text' => 'Older Posts <i class="fas fa-chevron-right"></i>',
                    'current'   => max(1, get_query_var('paged')),
                ));
                ?>
            </div>
        <?php else : ?>
            <p class="no-results">No posts found. Try a different tag!</p>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
    </div>
</main>

<?php get_footer(); ?>