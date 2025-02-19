<?php
get_header();

// Check if there are posts in the main loop
$has_main_posts = have_posts();
?>

<div class="page-container <?php echo $has_main_posts ? 'has-posts' : 'no-posts'; ?>">
  <!-- Main Page Content -->
  <div class="content-column">
    <!-- Page Title -->
    <?php if ($has_main_posts) : ?>
      <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <div class="post-header">
            <h2 class="post-title"><?php echo esc_html(get_the_title()); ?></h2>
          </div>
          <div class="post-content">
            <?php the_content(); ?>
          </div>
        </article>
      <?php endwhile; ?>
    <?php else : ?>
      <p><?php esc_html_e('No content found.', 'your-theme-textdomain'); ?></p>
    <?php endif; ?>
  </div>

  <!-- Sidebar with Recent Posts -->
  <aside class="sidebar-column">
    <h2><?php esc_html_e('Kapcsolodó hírek', 'your-theme-textdomain'); ?></h2>
    
    <?php
    // Get the current page slug and use it as a tag filter
    $page_slug = get_post_field('post_name', get_post());
    
    
    // Query for the most recent posts with the tag matching the page slug
    $recent_posts = new WP_Query(array(
      'posts_per_page'      => 3,
      'ignore_sticky_posts' => true,
      'tag'                 => sanitize_title($page_slug), // Use the sanitized version of the page slug
    ));

    if ($recent_posts->have_posts()) :
      while ($recent_posts->have_posts()) : $recent_posts->the_post();
    ?>
        <article class="sidebar-post">
          <header class="sidebar-post-header">
            <h3 class="sidebar-post-title">
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>
            <?php if (has_post_thumbnail()) : ?>
              <div class="sidebar-post-thumbnail">
                <a href="<?php the_permalink(); ?>">
                  <?php the_post_thumbnail('thumbnail'); ?>
                </a>
              </div>
            <?php endif; ?>
          </header>
          <div class="sidebar-post-excerpt">
            <?php the_excerpt(); ?>
            <a class="read-more" href="<?php the_permalink(); ?>">
              <?php esc_html_e('Bővebben', 'your-theme-textdomain'); ?>
            </a>
          </div>
        </article>
    <?php
      endwhile;
      wp_reset_postdata();
    else :
      echo '<p>' . esc_html__('No recent posts available.', 'your-theme-textdomain') . '</p>';
    endif;
    ?>
  </aside>
</div>

<?php
get_footer();
