<?php
// Include header
get_header();
?>

<main id="main-content">
    <div class="container">
        <!-- Tag Search and Sort Form -->
        <div class="tag-search">
            <form action="<?php echo esc_url(home_url('/')); ?>" method="get">
                <div class="input-group">
                    <input 
                        type="text" 
                        id="tag-search" 
                        name="tag" 
                        value="<?php echo get_query_var('tag'); ?>" 
                        placeholder=" " 
                    
                    />
                    <label for="tag-search">Írj be egy címkét... </label>
                </div>

                <div class="input-group">
                    <select id="order" name="order" >
                        <option value="ASC" <?php selected(get_query_var('order'), 'ASC'); ?>>Legrégebbi</option>
                        <option value="DESC" <?php selected(get_query_var('order'), 'DESC'); ?>>Legújabb</option>
                    </select>
                </div>

                <button type="submit">
                    Keresés és rendezés <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>

        <?php 
        // Query posts with sorting by date
        $order = get_query_var('order') ? get_query_var('order') : 'DESC';
        query_posts(array_merge($wp_query->query_vars, array(
            'order' => $order,
        )));
        ?>

        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('post-card'); ?>>
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
                    previous_posts_link('<i class="fas fa-chevron-left"></i> Newer Posts');
                    next_posts_link('Older Posts <i class="fas fa-chevron-right"></i>');
                ?>
            </div>
        <?php else : ?>
            <p class="no-results">No posts found. Try a different tag!</p>
        <?php endif; ?>
        
        <?php wp_reset_query(); ?>
    </div>
</main>

<?php
// Include footer
get_footer();
?>