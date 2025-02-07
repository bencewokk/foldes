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
                    <label for="tag-search">Írj be egy címkét...</label>
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

                <!-- Toggle Switch -->
                <div class="toggle-container">
                    <label class="toggle-switch-group">
                        <div class="toggle-switch-wrapper">
                            <?php
                            // Set the toggle to true by default if not provided in GET parameters.
                            $fontos_toggle = isset($_GET['fontos_toggle']) ? $_GET['fontos_toggle'] === '1' : true;
                            ?>
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
        $order = isset($_GET['order']) ? sanitize_text_field( $_GET['order'] ) : 'DESC';
        $tag   = isset($_GET['tag']) ? sanitize_text_field( $_GET['tag'] ) : '';
        // $fontos_toggle is already set above.
        $layout = isset($_GET['layout']) ? sanitize_text_field( $_GET['layout'] ) : 'list';

        $query_args = array_merge( $wp_query->query_vars, array(
            'order'         => $order,
            's'             => $tag,
            'tag'           => $tag ? $tag . '+hir' : 'hir',
            'tag_operator'  => 'AND',
        ));

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

            <div class="pagination">
                <?php 
                previous_posts_link( '<i class="fas fa-chevron-left"></i> Newer Posts', $custom_query->max_num_pages );
                next_posts_link( 'Older Posts <i class="fas fa-chevron-right"></i>', $custom_query->max_num_pages );
                ?>
            </div>
        <?php else : ?>
            <p class="no-results">No posts found. Try a different search!</p>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
    </div>
</main>

<?php get_footer(); ?>
