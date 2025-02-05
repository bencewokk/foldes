<?php
/**
 * Template Name: Tanáraink Grid
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php
        while (have_posts()) :
            the_post();
            get_template_part('template-parts/content', 'page');
        endwhile;
        ?>

        <!-- Search Form -->
        <div class="tag-search">
            <form role="search" method="get" action="<?php echo esc_url(get_permalink()); ?>">
                <div class="input-group">
                    <input type="text" 
                           name="teacher_search"
                           value="<?php echo isset($_GET['teacher_search']) ? esc_attr($_GET['teacher_search']) : ''; ?>"
                           placeholder=" ">
                    <label>Írj be egy nevet vagy végzettséget/tantárgyat...</label>
                </div>
                
                <button type="submit">
                    <?php echo esc_html_x('Keresés', 'submit button', 'textdomain'); ?>
                </button>
            </form>
        </div>
        
        <?php
        $search_query = isset($_GET['teacher_search']) ? sanitize_text_field($_GET['teacher_search']) : '';

        $query_args = array(
            'post_type'      => 'post',
            'tag'           => 'tanar',
            'posts_per_page' => -1,
            'orderby'       => 'menu_order',
            'order'         => 'ASC'
        );

        // Add search parameters if search is performed
        if (!empty($search_query)) {
            $query_args['s'] = $search_query;
        }

        $teacher_query = new WP_Query($query_args);

        if ($teacher_query->have_posts()) :
        ?>
            <div class="teachers-grid">
                <?php while ($teacher_query->have_posts()) : $teacher_query->the_post(); 
                    $teacher_title = get_post_meta(get_the_ID(), 'teacher_title', true);
                ?>
                    <article class="teacher-card">
                        <div class="image-container">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php the_post_thumbnail_url('medium_large'); ?>" 
                                     alt="<?php the_title_attribute(); ?>">
                            <?php endif; ?>
                            <div class="teacher-overlay">
                                <div class="teacher-overlay-content">
                                    <h4><?php echo esc_html($teacher_title); ?></h4>
                                    <div class="teacher-bio">
                                        <?php echo wp_kses_post(get_the_excerpt()); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="teacher-info">
                            <h3 class="teacher-name"><?php the_title(); ?></h3>
                            <a href="<?php echo esc_url(get_permalink()); ?>" class="teacher-button">
                                Bővebben
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <p class="no-teachers">
                <?php
                if (!empty($search_query)) {
                    printf(
                        esc_html__('Nincs találat erre: "%s"', 'textdomain'),
                        esc_html($search_query)
                    );
                } else {
                    esc_html_e('Nincsenek tanárok megjeleníthetőek.', 'textdomain');
                }
                ?>
            </p>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
?>