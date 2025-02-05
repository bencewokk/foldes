<?php
/**
 * Template Name: Tablo Articles
 */
get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php
        // Display page content.
        while ( have_posts() ) : the_post();
            if ( get_the_content() ) :
                ?>
                <article class="page-content">
                    <?php the_content(); ?>
                </article>
                <?php
            endif;
        endwhile;
        ?>

        <div class="tag-search">
            <form role="search" method="get" action="<?php echo esc_url( get_permalink() ); ?>">
                <div class="input-group">
                    <input type="text" 
                           name="tablo_search"
                           value="<?php echo isset( $_GET['tablo_search'] ) ? esc_attr( $_GET['tablo_search'] ) : ''; ?>"
                           placeholder=" ">
                    <label>Írj be egy nevet, vagy év/osztályt-et...</label>
                </div>
                <button type="submit">
                    <?php echo esc_html_x( 'Keresés', 'submit button', 'textdomain' ); ?>
                </button>
            </form>
        </div>

        <?php
        // Use the custom query variable 'custom_year' (instead of 'year').
        $search_query  = isset( $_GET['tablo_search'] ) ? sanitize_text_field( $_GET['tablo_search'] ) : '';
        $selected_year = isset( $_GET['custom_year'] ) ? sanitize_text_field( $_GET['custom_year'] ) : '';

        // If no search or year filter is set, show the year grid.
        if ( empty( $search_query ) && empty( $selected_year ) ) :

            // Get all posts tagged with 'tablo'
            $tablo_posts = get_posts( array(
                'post_type'      => 'post',
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'post_tag',
                        'field'    => 'slug',
                        'terms'    => 'tablo',
                    ),
                ),
            ) );

            // Get all tags for these posts.
            $all_terms = wp_get_object_terms( $tablo_posts, 'post_tag' );
            $years     = array();

            foreach ( $all_terms as $term ) {
                if ( preg_match( '/^\d{4}$/', $term->name ) ) {
                    $years[ $term->term_id ] = $term;
                }
            }

            // Sort years in descending order.
            usort( $years, function ( $a, $b ) {
                return $b->name - $a->name;
            } );

            if ( ! empty( $years ) ) : ?>
                <div class="years-grid">
                    <?php foreach ( $years as $year ) : ?>
                        <div class="year-item">
                            <a href="<?php echo add_query_arg( 'custom_year', $year->slug, get_permalink() ); ?>">
                                <?php echo esc_html( $year->name ); ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="no-years"><?php esc_html_e( 'No years found.', 'textdomain' ); ?></p>
            <?php endif;

        else :
            // Build query arguments for filtering by search and/or year.
            $paged      = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
            $query_args = array(
                'post_type'      => 'post',
                'posts_per_page' => 20,
                'paged'          => $paged,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'post_tag',
                        'field'    => 'slug',
                        'terms'    => 'tablo',
                    ),
                ),
            );

            if ( ! empty( $search_query ) ) {
                $query_args['s'] = $search_query;
            }

            if ( ! empty( $selected_year ) ) {
                $query_args['tax_query'][] = array(
                    'taxonomy' => 'post_tag',
                    'field'    => 'slug',
                    'terms'    => $selected_year,
                );
            }

            $tablo_query = new WP_Query( $query_args );

            if ( $tablo_query->have_posts() ) :

                // If a year is selected (and no search term), group all posts in one article.
                if ( ! empty( $selected_year ) && empty( $search_query ) ) : ?>
                    <article class="year-article">
                        <header>
                            <h2><?php printf( esc_html__( '%s', 'textdomain' ), esc_html( $selected_year ) ); ?></h2>
                        </header>
                        <div class="year-article-content">
                            <?php
                            while ( $tablo_query->have_posts() ) : $tablo_query->the_post();
                                // Get year and class from tags (if needed).
                                $all_terms = get_the_terms( get_the_ID(), 'post_tag' );
                                $year      = '';
                                $class     = '';

                                foreach ( (array) $all_terms as $term ) {
                                    if ( preg_match( '/^\d{4}$/', $term->name ) ) {
                                        $year = $term->name;
                                    } elseif ( preg_match( '/^\d{1,2}[a-zA-Z]$/', $term->name ) ) {
                                        $class = $term->name;
                                    }
                                }
                                ?>
                                <div class="year-article-post">
                                    <div class="post-excerpt">
                                        <?php the_content(); // Display full content instead of excerpt ?>
                                    </div>
                                </div>
                            <?php endwhile;
                            ?>
                        </div>
                    </article>
                <?php
                // Else, if a search term is provided, show each result as an individual card.
                elseif ( ! empty( $search_query ) ) : ?>
                    <div class="search-results">
                        <?php
                        while ( $tablo_query->have_posts() ) : $tablo_query->the_post();
                            // Get year and class from tags.
                            $all_terms = get_the_terms( get_the_ID(), 'post_tag' );
                            $year      = '';
                            $class     = '';
                            
                            
                            ?>
                            <article class="post-card">
                                <div class="post-header">
                                    <h2 class="post-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h2>
                                </div>

                                <div class="post-content">
                                    <?php the_content(); // Display full content instead of excerpt ?>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                <?php
                // Otherwise, display the posts as a grid.
                else : ?>
                    <div class="tablo-grid-container">
                        <?php
                        while ( $tablo_query->have_posts() ) : $tablo_query->the_post();
                            // Get year and class from tags.
                            $all_terms = get_the_terms( get_the_ID(), 'post_tag' );
                            $year      = '';
                            $class     = '';
                            
                            foreach ( (array) $all_terms as $term ) {
                                if ( preg_match( '/^\d{4}$/', $term->name ) ) {
                                    $year = $term->name;
                                } elseif ( preg_match( '/^\d{1,2}[a-zA-Z]$/', $term->name ) ) {
                                    $class = $term->name;
                                }
                            }
                            ?>
                            <div class="tablo-grid-item">
                                <h2 class="tablo-grid-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                <div class="tablo-grid-meta">
                                    <?php if ( $year ) : ?>
                                        <span class="tablo-grid-year">Year: <?php echo esc_html( $year ); ?></span>
                                    <?php endif; ?>
                                    <?php if ( $class ) : ?>
                                        <span class="tablo-grid-class">Class: <?php echo esc_html( $class ); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>

                <div class="tablo-pagination">
                    <?php
                    echo paginate_links( array(
                        'total'     => $tablo_query->max_num_pages,
                        'current'   => max( 1, $paged ),
                        'prev_text' => __( '« Previous', 'textdomain' ),
                        'next_text' => __( 'Next »', 'textdomain' ),
                    ) );
                    ?>
                </div>
            <?php else : ?>
                <p class="no-articles">
                    <?php
                    if ( ! empty( $search_query ) ) {
                        printf(
                            esc_html__( 'No tablo articles found for "%s".', 'textdomain' ),
                            esc_html( $search_query )
                        );
                    } else {
                        esc_html_e( 'No tablo articles found.', 'textdomain' );
                    }
                    ?>
                </p>
            <?php endif;
            wp_reset_postdata();
        endif;
        ?>
    </div>
</main>

<?php get_footer(); ?>
