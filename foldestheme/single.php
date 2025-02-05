<?php
get_header();
?>

<main id="main-content" class="container">
    <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class('post-card'); ?>>
            <header class="post-header">
                <h1 class="post-title"><?php the_title(); ?></h1>
            </header>

            <div class="post-content">
                <?php the_content(); ?>
            </div>

            <p class="post-meta">
                By <?php the_author(); ?> | <?php echo get_the_date(); ?>
            </p>
        </article>
    <?php endwhile; ?>
</main>

<?php
get_footer();