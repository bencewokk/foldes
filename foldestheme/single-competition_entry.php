<?php
/**
 * Single template for Competition Entry custom post type.
 */

get_header();
?>

<div class="competition-entry-single">
    <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <div class="entry-meta">
                        <span class="entry-date"><?php echo get_the_date(); ?></span>
                    </div>
                </header>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
                <footer class="entry-footer">
                    <?php // Additional footer content can go here ?>
                </footer>
            </article>
        <?php endwhile; ?>
    <?php else : ?>
        <p><?php esc_html_e( 'Sorry, no entries were found.', 'your-theme-textdomain' ); ?></p>
    <?php endif; ?>
</div>

<?php
get_footer();
?>
