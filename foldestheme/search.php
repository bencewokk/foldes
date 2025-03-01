<?php
/**
 * Template Name: Universal Search Page Template
 * Description: A flexible search page template that can display any post type with various filtering options
 */
get_header();
?>

<main id="main-content">
    <div class="container">
        <!-- Universal Search Form -->
        <div class="tag-search">
            <form action="<?php echo esc_url(home_url('/')); ?>" method="get">
                <!-- Search Input and Year Filter in One Row -->
                <div class="search-year-row">
                    <!-- Search Input -->
                    <div class="input-group search-group">
                        <input type="text" id="search-query" name="s" placeholder=" " value="<?php echo esc_attr(isset($_GET['s']) ? sanitize_text_field($_GET['s']) : ''); ?>">
                        <label for="search-query">Keresés bármire...</label>
                    </div>
                </div>

                <!-- Post Type Filter -->
                <div class="input-group">
                    <select id="post-type" name="post_type">
                        <!-- All Content option -->
                        <option value="any" <?php selected(isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : 'any', 'any'); ?>>Minden tartalom</option>
                        
                        <!-- Explicitly add Competition Entries if it exists -->
                        <?php if (post_type_exists('competition_entries')) : ?>
                            <option value="competition_entries" <?php selected(isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : '', 'competition_entries'); ?>>Verseny bejegyzések</option>
                        <?php endif; ?>
                        
                        <!-- Standard post types: Posts and Pages -->
                        <option value="post" <?php selected(isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : '', 'post'); ?>>Bejegyzések</option>
                        <option value="page" <?php selected(isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : '', 'page'); ?>>Oldalak</option>
                        
                        <!-- Dynamically add other public custom post types, excluding competition_entries -->
                        <?php
                        $custom_post_types = get_post_types(array('public' => true, '_builtin' => false), 'objects');
                        foreach ($custom_post_types as $post_type) {
                            if ($post_type->name !== 'competition_entries') {
                                printf(
                                    '<option value="%s" %s>%s</option>',
                                    esc_attr($post_type->name),
                                    selected(isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : '', $post_type->name, false),
                                    esc_html($post_type->labels->name)
                                );
                            }
                        }
                        ?>
                    </select>
                </div>

                <!-- Category Filter (shown only for post types that support categories) -->
                <div class="input-group category-filter">
                    <select id="category" name="category_name">
                        <option value="">Minden kategória</option>
                        <?php
                        $categories = get_categories(array('hide_empty' => false));
                        foreach ($categories as $category) {
                            printf(
                                '<option value="%s" %s>%s</option>',
                                esc_attr($category->slug),
                                selected(isset($_GET['category_name']) ? sanitize_text_field($_GET['category_name']) : '', $category->slug, false),
                                esc_html($category->name)
                            );
                        }
                        ?>
                    </select>
                </div>

                <!-- Tag Filter -->
                <div class="input-group tag-filter">
                    <input type="text" id="tag-filter" name="tag" placeholder=" " value="<?php echo esc_attr(isset($_GET['tag']) ? sanitize_text_field($_GET['tag']) : ''); ?>">
                    <label for="tag-filter">Szűrés címke szerint</label>
                </div>

               
                <!-- Order Options -->
                <div class="input-group">
                    <select id="orderby" name="orderby">
                        <option value="date" <?php selected(isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'date', 'date'); ?>>Dátum</option>
                        <option value="title" <?php selected(isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : '', 'title'); ?>>Cím</option>
                    </select>
                </div>

                <div class="input-group">
                    <select id="order" name="order">
                        <option value="DESC" <?php selected(isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'DESC', 'DESC'); ?>>Előlröl hátra</option>
                        <option value="ASC" <?php selected(isset($_GET['order']) ? sanitize_text_field($_GET['order']) : '', 'ASC'); ?>>Hátulról előre</option>
                    </select>
                </div>

                <!-- Layout Toggle -->
                <div class="layout-toggle">
                    <label for="layout">Megjelenítés:</label>
                    <select id="layout" name="layout">
                        <option value="grid" <?php selected(isset($_GET['layout']) ? sanitize_text_field($_GET['layout']) : 'grid', 'grid'); ?>>Rács</option>
                        <option value="list" <?php selected(isset($_GET['layout']) ? sanitize_text_field($_GET['layout']) : '', 'list'); ?>>Lista</option>
                    </select>
                </div>


                <!-- Submit Button -->
                <button type="submit" class="search-submit-btn">
                    Keresés <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <?php
        // Get query variables from the form
        $search_query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
        $post_type = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : 'any';
        $category = isset($_GET['category_name']) ? sanitize_text_field($_GET['category_name']) : '';
        $tag = isset($_GET['tag']) ? sanitize_text_field($_GET['tag']) : '';
        $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'date';
        $order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'DESC';
        $layout = isset($_GET['layout']) ? sanitize_text_field($_GET['layout']) : 'grid'; // Changed default to grid

        // Build the query arguments
        $query_args = array(
            'post_type' => $post_type,
            'posts_per_page' => 16,
            'orderby' => $orderby,
            'order' => $order,
        );

        // Add search query if provided
        if (!empty($search_query)) {
            $query_args['s'] = $search_query;
        }

        // Add category filter if provided
        if (!empty($category)) {
            $query_args['category_name'] = $category;
        }

        // Add tag filter if provided
        if (!empty($tag)) {
            $query_args['tag'] = $tag;
        }

        // For regular posts, also consider sticky posts
        if ($post_type == 'post' || $post_type == 'any') {
            $sticky_posts = get_option('sticky_posts');
            if (!empty($sticky_posts) && $order == 'DESC' && $orderby == 'date' && empty($search_query)) {
                $query_args['ignore_sticky_posts'] = 0;
            }
        }

        // Execute the query
        $custom_query = new WP_Query($query_args);
        ?>

        <!-- Combined Search Results Count / No Results Container -->
        <div class="no-results">
            <?php if ($custom_query->have_posts()) : ?>
                <h3>Keresési eredmények</h3>
                <p><?php echo esc_html($custom_query->found_posts); ?> találat</p>
            <?php else : ?>
                <h3>Nincs találat</h3>
                <p>Sajnáljuk, de nem találtunk a keresési feltételeknek megfelelő tartalmat. Kérjük, próbálja újra más paraméterekkel.</p>
            <?php endif; ?>
        </div>

        <?php if ($custom_query->have_posts()) : ?>
            <div class="results-container <?php echo esc_attr($layout === 'grid' ? 'grid-view' : 'list-view'); ?>">
                <?php while ($custom_query->have_posts()) : $custom_query->the_post(); ?>
                    <article <?php post_class('result-item'); ?>>
                        
                        <?php if (has_post_thumbnail() && $layout === 'grid') : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="result-content">
                            <header class="result-header">
                                <h2 class="result-title">
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>
                                <div class="result-meta">
                                    <span class="post-type-label"><?php echo get_post_type_object(get_post_type())->labels->singular_name; ?></span>
                                    <?php if (get_post_type() === 'post') : ?>
                                        <span class="result-date"><?php echo get_the_date(); ?></span>
                                    <?php endif; ?>
                                    <?php if (get_post_type() === 'post') : ?>
                                        <span class="result-author">Írta: <?php the_author(); ?></span>
                                    <?php endif; ?>
                                </div>
                            </header>

                            <div class="result-excerpt">
                                <?php 
                                // Show excerpt for all layouts for consistency
                                if (has_excerpt()) {
                                    the_excerpt();
                                } else {
                                    echo wp_trim_words(get_the_content(), 30, '...');
                                }
                                ?>
                            </div>

                            <?php if (get_post_type() === 'post') : ?>
                                <div class="result-taxonomy">
                                    <?php 
                                    // Display categories
                                    $categories = get_the_category();
                                    if (!empty($categories)) {
                                        echo '<div class="result-categories">';
                                        foreach ($categories as $category) {
                                            echo '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
                                        }
                                        echo '</div>';
                                    }
                                    
                                    // Display tags
                                    $tags = get_the_tags();
                                    if (!empty($tags)) {
                                        echo '<div class="result-tags">';
                                        foreach ($tags as $tag) {
                                            echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '">' . esc_html($tag->name) . '</a>';
                                        }
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <?php 
                echo paginate_links(array(
                    'base' => add_query_arg('paged', '%#%'),
                    'format' => '',
                    'prev_text' => __('<i class="fas fa-chevron-left"></i> Előző'),
                    'next_text' => __('Következő <i class="fas fa-chevron-right"></i>'),
                    'total' => $custom_query->max_num_pages,
                    'current' => max(1, get_query_var('paged'))
                ));
                ?>
            </div>
        <?php else : ?>
            
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
    </div>
</main>

<script>
jQuery(document).ready(function($) {
    // Show/hide category filter based on post type selection
    $('#post-type').on('change', function() {
        var selectedValue = $(this).val();
        if (selectedValue === 'post' || selectedValue === 'any') {
            $('.category-filter').show();
        } else {
            $('.category-filter').hide();
        }
    });
    
    // Initialize the filter visibility based on current selection
    var currentPostType = $('#post-type').val();
    if (currentPostType === 'post' || currentPostType === 'any') {
        $('.category-filter').show();
    } else {
        $('.category-filter').hide();
    }
});
</script>

<?php get_footer(); ?>