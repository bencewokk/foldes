<?php
/**
 * Template Name: Tablo Articles
 */
get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php
        // Display page content if it's a page
        while (have_posts()) : the_post();
            if (get_the_content()) :
                ?>
                <article class="page-content">
                    <?php the_content(); ?>
                </article>
                <?php
            endif;
        endwhile;
        ?>

        <!-- Search Form -->
        <form role="search" method="get" class="tablo-search-form" action="<?php echo esc_url(get_permalink()); ?>">
            <input type="search" class="search-field"
                placeholder="<?php echo esc_attr_x('Search Tablo Articles...', 'placeholder', 'textdomain'); ?>"
                value="<?php echo get_search_query(); ?>"
                name="tablo_search">
            <button type="submit" class="search-submit">
                <?php echo esc_html_x('Search', 'submit button', 'textdomain'); ?>
            </button>
        </form>

        <?php
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $search_query = isset($_GET['tablo_search']) ? sanitize_text_field($_GET['tablo_search']) : '';

        $query_args = array(
            'post_type' => 'post',
            'tag' => 'tablo',
            'posts_per_page' => 20,
            'paged' => $paged,
            'orderby' => 'date',
            'order' => 'DESC'
        );

        if (!empty($search_query)) {
            $query_args['s'] = $search_query;
        }

        $tablo_query = new WP_Query($query_args);

        if ($tablo_query->have_posts()) :
            if (!empty($search_query)) : // If there's a search match
                ?>
                <div class="search-results">
                    <?php
                    while ($tablo_query->have_posts()) : $tablo_query->the_post();
                        ?>
                        <article class="post-card">
                            <div class="post-header">
                                <h2 class="post-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                            </div>
                            <div class="post-content">
                                <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                            </div>
                        </article>
                        <?php
                    endwhile;
                    ?>
                </div>
            <?php
            else : // Normal grid view without search
                ?>
                <div class="tablo-grid-container">
                    <?php
                    while ($tablo_query->have_posts()) : $tablo_query->the_post();
                        ?>
                        <div class="tablo-grid-item">
                            <h2 class="tablo-grid-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                        </div>
                        <?php
                    endwhile;
                    ?>
                </div>
            <?php
            endif;
            ?>

            <div class="tablo-pagination">
                <?php
                echo paginate_links(array(
                    'total' => $tablo_query->max_num_pages,
                    'current' => max(1, $paged),
                    'prev_text' => __('« Previous'),
                    'next_text' => __('Next »'),
                ));
                ?>
            </div>
        <?php else : ?>
            <p class="no-articles">
                <?php
                if (!empty($search_query)) {
                    printf(
                        esc_html__('No tablo articles found for "%s".', 'textdomain'),
                        esc_html($search_query)
                    );
                } else {
                    esc_html_e('No tablo articles found.', 'textdomain');
                }
                ?>
            </p>
        <?php
        endif;
        wp_reset_postdata();
        ?>
    </div>
</main>

<?php get_footer(); ?>