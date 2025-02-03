<?php
/**
 * Template Name: Tag Search and Sort Template
 */
get_header();
?>

<main id="main-content">
    <div class="container">
        <!-- Tag Search and Sort Form -->
        <div class="tag-search">
            <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
                <!-- Search Input -->
                <div class="input-group searchbar-group">
                    <input type="text" id="tag-search" name="tag" placeholder=" ">
                    <label for="tag-search">Írj be egy címkét...</label>
                </div>

                <!-- Order Select -->
                <div class="input-group">
                    <select id="order" name="order">
                        <option value="DESC">Legújabb</option>
                        <option value="ASC">Legrégebbi</option>
                    </select>
                </div>

                <!-- Toggle Switch -->
                <div class="toggle-container">
                    <label class="toggle-switch-group">
                        <div class="toggle-switch-wrapper">
                            <input type="checkbox" class="toggle-switch-input" name="fontos_toggle" value="1">
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
        // Set up query arguments from URL parameters.
        $order          = get_query_var('order') ? get_query_var('order') : 'DESC';
        $tag            = get_query_var('tag') ? sanitize_text_field( get_query_var('tag') ) : '';
        $fontos_toggle  = isset( $_GET['fontos_toggle'] ) && $_GET['fontos_toggle'] === '1';

        // Merge any existing query vars with our new arguments.
        $query_args = array_merge( $wp_query->query_vars, array(
            'order' => $order,
            'tag'   => $tag,
        ));

        // If the Fontos toggle is enabled, modify the SQL clauses.
        if ( $fontos_toggle ) {
            add_filter( 'posts_clauses', function ( $clauses ) {
                global $wpdb;
                $fontos_term = get_term_by( 'name', 'fontos', 'post_tag' );
                if ( $fontos_term ) {
                    $term_taxonomy_id = intval( $fontos_term->term_taxonomy_id );
                    // Join the term_relationships table with a date check.
                    $clauses['join'] .= " LEFT JOIN {$wpdb->term_relationships} AS fontos_tr 
                        ON {$wpdb->posts}.ID = fontos_tr.object_id 
                        AND fontos_tr.term_taxonomy_id = {$term_taxonomy_id}
                        AND {$wpdb->posts}.post_date >= DATE_SUB(NOW(), INTERVAL 6 WEEK)";
                    // Order posts: those matching the Fontos tag first.
                    $clauses['orderby'] = "fontos_tr.object_id IS NOT NULL DESC, " . $clauses['orderby'];
                }
                return $clauses;
            } );
        }

        // Use a custom WP_Query instead of query_posts.
        $custom_query = new WP_Query( $query_args );
        ?>

        <?php if ( $custom_query->have_posts() ) : ?>
            <?php while ( $custom_query->have_posts() ) : $custom_query->the_post(); ?>
                <article <?php post_class( 'post-card' ); ?>>
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
                // Pagination with previous and next links.
                previous_posts_link( '<i class="fas fa-chevron-left"></i> Newer Posts', $custom_query->max_num_pages );
                next_posts_link( 'Older Posts <i class="fas fa-chevron-right"></i>', $custom_query->max_num_pages );
                ?>
            </div>
        <?php else : ?>
            <p class="no-results">No posts found. Try a different tag!</p>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
    </div>
</main>

<?php get_footer(); ?>
