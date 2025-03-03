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
                    <input type="text" id="tag-search" name="tag" placeholder=" " value="<?php echo esc_attr( isset($_GET['tag']) ? sanitize_text_field( $_GET['tag'] ) : '' ); ?>">
                    <label for="tag-search">Keress rá bármire...</label>
                </div>

                <!-- Order Select -->
                <div class="input-group">
                    <select id="order" name="order">
                        <option value="DESC" <?php selected( isset($_GET['order']) ? sanitize_text_field( $_GET['order'] ) : 'DESC', 'DESC' ); ?>>Legújabb</option>
                        <option value="ASC" <?php selected( isset($_GET['order']) ? sanitize_text_field( $_GET['order'] ) : '', 'ASC' ); ?>>Legrégebbi</option>
                    </select>
                </div>

                <!-- Layout Toggle -->
                <div class="layout-toggle">
                    <label for="layout">Megjelenítés:</label>
                    <select id="layout" name="layout">
                        <option value="list" <?php selected( isset($_GET['layout']) ? sanitize_text_field($_GET['layout']) : 'list', 'list' ); ?>>Lista</option>
                        <option value="grid" <?php selected( isset($_GET['layout']) ? sanitize_text_field($_GET['layout']) : '', 'grid' ); ?>>Rács</option>
                    </select>
                </div>

                <!-- Toggle Switch (Fontos hírek elől) -->
                <div class="toggle-container">
                    <label class="toggle-switch-group">
                        <div class="toggle-switch-wrapper">
                            <?php
                            $fontos_toggle = isset($_GET['fontos_toggle']) ? ($_GET['fontos_toggle'] === '1') : true;
                            ?>
                            <input type="hidden" name="fontos_toggle" value="0">
                            <input type="checkbox" class="toggle-switch-input" name="fontos_toggle" value="1" <?php checked( $fontos_toggle ); ?>>
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
        // Get query variables from the form.
        $order  = isset($_GET['order'])  ? sanitize_text_field( $_GET['order'] )  : 'DESC';
        $tag    = isset($_GET['tag'])    ? sanitize_text_field( $_GET['tag'] )    : '';
        // $fontos_toggle is already set above.
        $layout = isset($_GET['layout']) ? sanitize_text_field( $_GET['layout'] ) : 'list';

        // Define the current page for pagination.
        $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

        // Build the basic query arguments.
        $query_args = array_merge( $wp_query->query_vars, array(
            'order'         => $order,
            's'             => $tag,
            'tag'           => $tag ? $tag . '+hir' : 'hir',
            'tag_operator'  => 'AND',
            'paged'         => $paged, // Added pagination support
        ));

        // Add date query if at least one year is provided.
        $from_year = isset($_GET['from_year']) && !empty($_GET['from_year']) ? intval($_GET['from_year']) : null;
        $to_year   = isset($_GET['to_year'])   && !empty($_GET['to_year'])   ? intval($_GET['to_year'])   : null;

        if ( $from_year || $to_year ) {
            $date_query = array();
            if ( $from_year ) {
                $date_query['after'] = array(
                    'year'  => $from_year,
                    'month' => 1,
                    'day'   => 1,
                );
            }
            if ( $to_year ) {
                $date_query['before'] = array(
                    'year'  => $to_year,
                    'month' => 12,
                    'day'   => 31,
                );
            }
            $query_args['date_query'] = array( $date_query );
        }

        // Apply the 'fontos' filter if the toggle is on.
        if ( $fontos_toggle ) {
            add_filter( 'posts_clauses', function ( $clauses ) {
                global $wpdb;
                $fontos_term = get_term_by( 'name', 'fontos', 'post_tag' );
                if ( $fontos_term ) {
                    $term_taxonomy_id = intval( $fontos_term->term_taxonomy_id );
                    $clauses['join'] .= " LEFT JOIN {$wpdb->term_relationships} AS fontos_tr 
                        ON {$wpdb->posts}.ID = fontos_tr.object_id 
                        AND fontos_tr.term_taxonomy_id = {$term_taxonomy_id}
                        AND {$wpdb->posts}.post_date >= DATE_SUB(NOW(), INTERVAL 6 WEEK)";
                    $clauses['orderby'] = "fontos_tr.object_id IS NOT NULL DESC, " . $clauses['orderby'];
                }
                return $clauses;
            } );
        }

        $custom_query = new WP_Query( $query_args );
        ?>

        <?php if ( $custom_query->have_posts() ) : ?>
            <div class="post-container <?php echo esc_attr( $layout === 'grid' ? 'grid-view' : 'list-view' ); ?>">
                <?php while ( $custom_query->have_posts() ) : $custom_query->the_post(); ?>
                    <article <?php post_class( 'post-card' ); ?>>
                        <?php if ( $fontos_toggle && has_tag( 'fontos' ) ) : ?>
                            <div class="fontos-badge">Fontos</div>
                        <?php endif; ?>

                        <header class="post-header">
                            <h2 class="post-title">
                                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                        </header>

                        <div class="post-content">
                            <?php
                            // If grid view is enabled, show the excerpt; otherwise show full content.
                            if ( 'grid' === $layout ) {
                                the_excerpt();
                            } else {
                                the_content();
                            }
                            ?>
                        </div>

                        <p class="post-meta">
                            By <?php the_author(); ?> | <?php echo get_the_date(); ?>
                        </p>
                    </article>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <?php 
                echo paginate_links( array(
                    'base'      => add_query_arg( 'paged', '%#%' ),
                    'format'    => '',
                    'current'   => $paged,
                    'total'     => $custom_query->max_num_pages,
                    'prev_text' => '<i class="fas fa-chevron-left"></i> Newer Posts',
                    'next_text' => 'Older Posts <i class="fas fa-chevron-right"></i>',
                ) );
                ?>
            </div>
        <?php else : ?>
            <p class="no-results">Nem találtunk semmit itt sajnos :(</p>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
    </div>
</main>

<?php get_footer(); ?>
