<?php
get_header();
$has_main_posts = have_posts();

// Get recent posts query parameters
$page_slug = get_post_field('post_name', get_post());
$recent_posts = new WP_Query(array(
    'posts_per_page'      => 3,
    'ignore_sticky_posts' => true,
    'tag'                 => sanitize_title($page_slug),
    'date_query'          => array(
        array(
            'after'     => '1 month ago',
            'inclusive' => true
        )
    )
));
$has_recent_posts = $recent_posts->have_posts();
?>
<div class="page-container <?php echo !$has_recent_posts ? 'sidebar-collapsed' : ''; ?>">
  <div class="content-wrapper">
    <div class="content-column">
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
  </div>

  <aside class="sidebar-column">
    <button id="sidebar-toggle" class="sidebar-toggle-btn" aria-label="Toggle Sidebar">
      <span class="toggle-icon">›</span>
      <span class="toggle-news-icon">📰</span>
    </button>
    <div class="sidebar-content">
      <h2><?php esc_html_e('Kapcsolodó hírek', 'your-theme-textdomain'); ?></h2>
      <?php if ($recent_posts->have_posts()) : ?>
        <?php while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
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
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
      <?php else : ?>
        <p><?php esc_html_e('Mivel nincsenek most ehhez az oldalhoz kapcsolódó friss hírek, mit szólnál egy kis kreatív időtöltéshez? Képzeljük el, milyen hírek lennének, ha például a macskák uralnák a világot, vagy ha az időutazás mindennapos lenne! És ha ez nem érdekel, nyugodtan bezárhatod a híreket.', 'your-theme-textdomain'); ?></p>
      <?php endif; ?>
    </div>
  </aside>
</div>

<style>
/* Overall container remains a flex container */
.page-container {
  display: flex;
  gap: 20px;
  width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
  min-height: 100vh;
}

/* New container for the main content */
.content-wrapper {
  flex: 1;
}

/* Main content column */
.content-column {
  transition: all 0.3s ease;
  min-width: 0;
}

/* Make the sidebar a positioning context */
.sidebar-column {
  position: sticky;
  top: 85px;
  width: 300px;
  min-width: 300px;
  background: var(--background-color);
  padding: 20px;
  max-height: calc(100vh - 40px);
  overflow-y: auto;
  transition: all 0.3s ease;
}

.sidebar-content {
  width: 100%;
  transition: opacity 0.3s ease;
}

.sidebar-post-excerpt {
  padding: 10px;
}

.sidebar-post-title {
  background-color: var(--primary-color);
  padding: 5px;
}

/* Default state: transparent background and vertically centered */
.sidebar-toggle-btn {
  position: absolute;
  left: -4px;
  top: 50%;
  transform: translateY(-50%);
  z-index: 100;
  background: transparent;
  border: 1px transparent;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.toggle-icon, .toggle-news-icon {
  display: inline-block;
  transition: transform 0.3s ease;
  font-size: 20px;
  line-height: 1;
}

.toggle-news-icon {
  display: none;
  font-size: 18px; /* Slightly smaller for better proportion */
}

/* Collapsed state adjustments */
.page-container.sidebar-collapsed .content-column {
  margin-right: 0;
}

.page-container.sidebar-collapsed .sidebar-column {
  transform: translateX(0);
  width: 30px;
  height: 30px;
  min-width: 30px;
  padding: 0;
  border-left: none;
}

.page-container.sidebar-collapsed .sidebar-content {
  opacity: 0;
}

/* Collapsed state: maintain vertical centering */
.page-container.sidebar-collapsed .sidebar-toggle-btn {
  left: 0;
}

.page-container.sidebar-collapsed .toggle-icon {
  display: none;
}

.page-container.sidebar-collapsed .toggle-news-icon {
  display: block;
  transform: rotate(0deg); /* Reset any rotation */
}

/* Hide scrollbar for Chrome, Safari and Opera */
.sidebar-column::-webkit-scrollbar {
  display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.sidebar-column {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;     /* Firefox */
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const toggleBtn = document.getElementById('sidebar-toggle');
  const container = document.querySelector('.page-container');
  const hasRecentPosts = <?php echo $has_recent_posts ? 'true' : 'false'; ?>;

  // Only check localStorage if there are recent posts
  if (hasRecentPosts) {
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    container.classList.toggle('sidebar-collapsed', isCollapsed);
  }

  toggleBtn.addEventListener('click', function() {
    container.classList.toggle('sidebar-collapsed');
    
    // Only save state if there are recent posts
    if (hasRecentPosts) {
      localStorage.setItem('sidebarCollapsed', container.classList.contains('sidebar-collapsed'));
    }
  });
});
</script>

<?php
get_footer();