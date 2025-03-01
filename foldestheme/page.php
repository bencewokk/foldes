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

<!-- Desktop Layout - Original HTML Structure -->
<div class="desktop-layout page-container <?php echo !$has_recent_posts ? 'sidebar-collapsed' : ''; ?>">
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
      <span class="toggle-icon"></span>
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

<!-- Mobile Layout - New HTML Structure -->
<div class="mobile-layout">
  
  
  <!-- Mobile News Section -->
  <div class="mobile-news-section">
    <button id="mobile-news-toggle" class="mobile-news-toggle-btn" aria-expanded="false">
      <span class="mobile-news-toggle-text"><?php esc_html_e('Kapcsolodó hírek', 'your-theme-textdomain'); ?></span>
      <span class="mobile-news-toggle-icon">↓</span>
    </button>
    
    <div class="mobile-news-content">
      <?php if ($recent_posts->have_posts()) : ?>
        <div class="mobile-news-items">
          <?php while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
            <div class="mobile-news-item">
              <div class="mobile-news-item-header">
                <?php if (has_post_thumbnail()) : ?>
                  <div class="mobile-news-thumbnail">
                    <a href="<?php the_permalink(); ?>">
                      <?php the_post_thumbnail('thumbnail'); ?>
                    </a>
                  </div>
                <?php endif; ?>
                <h3 class="mobile-news-title">
                  <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h3>
              </div>
              <div class="mobile-news-excerpt">
                <?php the_excerpt(); ?>
                <a class="mobile-read-more" href="<?php the_permalink(); ?>">
                  <?php esc_html_e('Bővebben', 'your-theme-textdomain'); ?>
                </a>
              </div>
            </div>
          <?php endwhile; ?>
          <?php wp_reset_postdata(); ?>
        </div>
      <?php else : ?>
        <p><?php esc_html_e('Mivel nincsenek most ehhez az oldalhoz kapcsolódó friss hírek, mit szólnál egy kis kreatív időtöltéshez? Képzeljük el, milyen hírek lennének, ha például a macskák uralnák a világot, vagy ha az időutazás mindennapos lenne! És ha ez nem érdekel, nyugodtan bezárhatod a híreket.', 'your-theme-textdomain'); ?></p>
      <?php endif; ?>
    </div>
  </div>

<!-- Main Content -->
  <div class="mobile-content">
    <?php if ($has_main_posts) : ?>
      <?php while (have_posts()) : the_post(); ?>
        <article id="mobile-post-<?php the_ID(); ?>" <?php post_class('mobile-article'); ?>>
          <div class="mobile-post-header">
            <h2 class="mobile-post-title"><?php echo esc_html(get_the_title()); ?></h2>
          </div>
          <div class="mobile-post-content">
            <?php the_content(); ?>
          </div>
        </article>
      <?php endwhile; ?>
    <?php else : ?>
      <p><?php esc_html_e('No content found.', 'your-theme-textdomain'); ?></p>
    <?php endif; ?>
  </div>
</div>

<style>
/* Mobile Layout Styles */
.mobile-layout {
  display: none;
  flex-direction: column;
  width: 100%;
  max-width: 100%;
  padding: 15px;
  margin: 0 auto;
}

/* Mobile Content Styles */
.mobile-content {
  width: 100%;
  margin-bottom: 30px;
}

.mobile-article {
  background-color: var(--background-color);
  margin: 0 0 30px 0;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  width: 100%;
  break-inside: avoid;
}

.mobile-post-header {
  width: auto;
  padding: 10px;
  color: var(--background-color);
  background: var(--primary-color);
}

.mobile-post-title {
  font-size: 1.8em;
  font-weight: bold;
  color: var(--white);
  text-align: center;
}

.mobile-post-content {
  padding: 20px;
  font-size: 1.1em;
  line-height: 1.6;
  color: var(--text-color);
}

.mobile-post-content p {
  margin-bottom: 1.5em;
}

/* Mobile News Section Styles */
.mobile-news-section {
  width: 100%;
  margin-bottom: 25px;
  background: var(--background-color);
  border-radius: 8px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.mobile-news-toggle-btn {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  padding: 15px 20px;
  background: var(--primary-color);
  color: var(--white);
  border: none;
  font-size: 1.2em;
  font-weight: bold;
  text-align: left;
  cursor: pointer;
}

.mobile-news-toggle-icon {
  transition: transform 0.3s ease;
}

.mobile-news-section.active .mobile-news-toggle-icon {
  transform: rotate(180deg);
}

.mobile-news-content {
  display: none;
  padding: 20px;
}

.mobile-news-section.active .mobile-news-content {
  display: block;
}

.mobile-news-items {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.mobile-news-item {
  padding-bottom: 20px;
  border-bottom: 1px solid var(--border-color, #ddd);
}

.mobile-news-item:last-child {
  padding-bottom: 0;
  border-bottom: none;
}

.mobile-news-item-header {
  display: flex;
  flex-direction: column;
  margin-bottom: 10px;
}

.mobile-news-thumbnail {
  margin-bottom: 10px;
}

.mobile-news-thumbnail img {
  max-width: 100%;
  height: auto;
  border-radius: 4px;
}

.mobile-news-title {
  font-size: 1.2em;
  font-weight: bold;
  margin-bottom: 10px;
}

.mobile-news-title a {
  color: var(--primary-color);
  text-decoration: none;
  transition: color 0.3s ease;
}

.mobile-news-title a:hover {
  color: var(--secondary-color);
  text-decoration: underline;
}

.mobile-news-excerpt {
  font-size: 0.9em;
  line-height: 1.5;
  color: var(--text-color);
}

.mobile-read-more {
  display: inline-block;
  margin-top: 10px;
  color: var(--secondary-color);
  text-decoration: none;
  transition: color 0.3s ease;
}

.mobile-read-more:hover {
  color: var(--highlight-color);
  text-decoration: underline;
}

/* Media Query to Switch Layouts */
@media screen and (max-width: 768px) {
  .desktop-layout {
    display: none;
  }
  
  .mobile-layout {
    display: flex;
  }
}
</style>

<script>
// Desktop sidebar toggle
document.addEventListener('DOMContentLoaded', function() {
  const toggleBtn = document.getElementById('sidebar-toggle');
  const container = document.querySelector('.page-container');
  const hasRecentPosts = <?php echo $has_recent_posts ? 'true' : 'false'; ?>;

  // Only check localStorage if there are recent posts
  if (hasRecentPosts && toggleBtn) {
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    container.classList.toggle('sidebar-collapsed', isCollapsed);
  }

  if (toggleBtn) {
    toggleBtn.addEventListener('click', function() {
      container.classList.toggle('sidebar-collapsed');
      
      // Only save state if there are recent posts
      if (hasRecentPosts) {
        localStorage.setItem('sidebarCollapsed', container.classList.contains('sidebar-collapsed'));
      }
    });
  }

  // Mobile news toggle
  const mobileNewsToggle = document.getElementById('mobile-news-toggle');
  const mobileNewsSection = document.querySelector('.mobile-news-section');
  
  if (mobileNewsToggle && mobileNewsSection) {
    // Check localStorage for mobile news state
    if (hasRecentPosts) {
      const isMobileNewsOpen = localStorage.getItem('mobileNewsOpen') === 'true';
      if (isMobileNewsOpen) {
        mobileNewsSection.classList.add('active');
        mobileNewsToggle.setAttribute('aria-expanded', 'true');
      }
    }
    
    mobileNewsToggle.addEventListener('click', function() {
      mobileNewsSection.classList.toggle('active');
      
      const isExpanded = mobileNewsSection.classList.contains('active');
      mobileNewsToggle.setAttribute('aria-expanded', isExpanded.toString());
      
      // Only save state if there are recent posts
      if (hasRecentPosts) {
        localStorage.setItem('mobileNewsOpen', isExpanded);
      }
    });
  }
});
</script>

<?php
get_footer();