<?php
// Include header
get_header();
?>

<main id="main-content">
    <div class="container">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class(); ?>>
                    <header class="post-header">
                        <h2 class="post-title">
                            <?php the_title(); ?>
                        </h2>
                        <div class="post-meta">
                            <span class="author"><?php the_author(); ?></span> |
                            <span class="date"><?php the_date(); ?></span>
                        </div>
                    </header>

                    <div class="post-content">
                        <?php the_content(); ?>
                    </div>
                </article>
            <?php endwhile; ?>
            <div class="pagination">
                <img src="foldestheme/banner.png" alt="Banner">
                
                <?php
                    // Previous page link
                    previous_posts_link('Newer Posts');
                    // Next page link
                    next_posts_link('Older Posts');
                ?>
            </div>
        <?php else : ?>
            <p>No posts found.</p>
        <?php endif; ?>
    </div>
</main>

<?php
// Include footer
get_footer();
?>
