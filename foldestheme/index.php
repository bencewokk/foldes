<?php
/**
 * Template Name: Tag Search and Sort Template
 */
get_header();
?>

<main id="main-content">
    <div class="container">

        <?php
        // Query for main highlighted posts
        $main_highlight_args = array(
            'tag'            => 'highlight',
            'posts_per_page' => 3   , // Show only the most recent highlighted post
            'orderby'        => 'date',
            'order'          => 'DESC'
        );
        $main_highlight_query = new WP_Query($main_highlight_args);

        // Query for upcoming events – removed meta_key and meta_query so it now just fetches posts with the tag "esemenyek"
        $events_args = array(
            'tag'            => 'esemenyek',
            'posts_per_page' => 3, // Show up to 3 upcoming events
            'orderby'        => 'date',
            'order'          => 'ASC'
        );
        $events_query = new WP_Query($events_args);
        ?>  

        <section class="highlights-and-events-section">
            <div class="highlights-events-container">
                <!-- Main Highlight Section with Cycling -->
                <div class="main-highlight-container">
                    <?php if ($main_highlight_query->have_posts()) : ?>
                        <div class="highlight-slider">
                            <?php while ($main_highlight_query->have_posts()) : $main_highlight_query->the_post(); ?>
                                <article class="main-highlight-post">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="main-highlight-image">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('large'); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
        
                                    <div class="main-highlight-content">
                                        <h2 class="main-highlight-title">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_title(); ?>
                                            </a>
                                        </h2>
                                        <div class="main-highlight-excerpt">
                                            <?php the_excerpt(); ?>
                                        </div>
                                    </div>
                                </article>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>
        
                        <!-- Cycle Controls -->
                        <div class="cycle-controls">
                            <button id="cycleToggle" class="cycle-button">
                                <i class="fas fa-pause"></i>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
        
                <!-- Upcoming Events Section -->
                <div class="upcoming-events-container">
                    <h3 class="upcoming-events-title">Közelgő Események</h3>
                    <?php 
                    $valid_event_count = 0;
                    if ($events_query->have_posts()) : ?>
                        <div class="calendar-timeline">
                            <?php while ($events_query->have_posts()) : $events_query->the_post(); 

                                // Look through tags to find one matching pattern: dateYYYY-MM-DD
                                $event_date = '';
                                $tags = get_the_tags();
                                if ($tags) {
                                    foreach ($tags as $tag) {
                                        if (preg_match('/^date(\d{4}-\d{2}-\d{2})$/', $tag->name, $matches)) {
                                            $event_date = $matches[1];
                                            break;
                                        }
                                    }
                                }

                                // Skip events without a valid future date.
                                if (!$event_date || strtotime($event_date) < strtotime(date('Y-m-d'))) {
                                    continue;
                                }
                                $valid_event_count++;
                                
                                $event_day = date('d', strtotime($event_date));
                                $event_month = date('M', strtotime($event_date));
                            ?>
                                <div class="calendar-event">
                                    <!-- Timeline elements -->
                                    <div class="timeline">
                                        <div class="timeline-dot"></div>
                                        <?php if ($valid_event_count < 3) : ?>
                                            <div class="timeline-line"></div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Date box -->
                                    <div class="event-date">
                                        <div class="date-day"><?php echo $event_day; ?></div>
                                        <div class="date-month"><?php echo $event_month; ?></div>
                                    </div>
                                    
                                    <!-- Event details -->
                                    <div class="event-details">
                                        <h4 class="event-title">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_title(); ?>
                                            </a>
                                        </h4>
                                        <?php 
                                        // Check if this event also has the "fontos" tag and display a minimal badge.
                                        if (has_tag('fontos')) : ?>
                                            <span class="fontos-event-badge">Fontos</span>
                                        <?php endif; ?>
                                        <div class="event-time">
                                            <?php echo date('H:i', strtotime($event_date)); ?>
                                        </div>
                                        <div class="event-excerpt">
                                            <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        <?php if ($valid_event_count === 0) : ?>
                            <p class="no-events">Jelenleg nincsenek közelgő események.</p>
                        <?php endif; ?>
                    <?php else : ?>
                        <p class="no-events">Jelenleg nincsenek közelgő események.</p>
                    <?php endif; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
            </div>
        </section>

        

        <script>
    document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.main-highlight-post');
    let currentSlide = 0;
    let cycleInterval;
    
    function showSlide(index) {
        currentSlide = (index + slides.length) % slides.length;
        slides.forEach((slide) => {
            slide.style.display = 'none';
            slide.classList.remove('active');
        });
        slides[currentSlide].style.display = 'block';
        setTimeout(() => slides[currentSlide].classList.add('active'), 10);
    }
    
    function nextSlide() {
        showSlide(currentSlide + 1);
    }
    
    function prevSlide() {
        showSlide(currentSlide - 1);
    }
    
    function startCycle() {
        // Clear any existing interval
        if (cycleInterval) {
            clearInterval(cycleInterval);
        }
        
        // Start new interval
        cycleInterval = setInterval(nextSlide, 5000);
    }
    
    // Initialize slider
    if (slides.length > 0) {
        // Remove existing cycle controls
        const cycleControls = document.querySelector('.cycle-controls');
        if (cycleControls) {
            cycleControls.remove();
        }
        
        // Create navigation buttons
        const navigationContainer = document.createElement('div');
        navigationContainer.className = 'highlight-navigation';
        
        const prevButton = document.createElement('button');
        prevButton.className = 'highlight-prev';
        prevButton.setAttribute('aria-label', 'Previous slide');
        prevButton.innerHTML = '&#10094;'; // Left arrow
        prevButton.addEventListener('click', () => {
            prevSlide();
            startCycle();
        });

        const nextButton = document.createElement('button');
        nextButton.className = 'highlight-next';
        nextButton.setAttribute('aria-label', 'Next slide');
        nextButton.innerHTML = '&#10095;'; // Right arrow
        nextButton.addEventListener('click', () => {
            nextSlide();
            startCycle();
        });
        
        navigationContainer.appendChild(prevButton);
        navigationContainer.appendChild(nextButton);
        
        const mainHighlightContainer = document.querySelector('.main-highlight-container');
        mainHighlightContainer.appendChild(navigationContainer);
        
        // Initial setup
        showSlide(0);
        startCycle();
    }
    
    // Pause on hover
    const sliderContainer = document.querySelector('.highlight-slider');
    if (sliderContainer) {
        sliderContainer.addEventListener('mouseenter', () => {
            clearInterval(cycleInterval);
        });
        sliderContainer.addEventListener('mouseleave', () => {
            startCycle();
        });
    }
});
        </script>

        <!-- Tag Search and Sort Form -->
        <div class="tag-search">
            <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
                <!-- Search Input -->
                <div class="input-group searchbar-group">
                    <input type="text" id="tag-search" name="tag" placeholder=" " value="<?php echo esc_attr( isset($_GET['tag']) ? sanitize_text_field( $_GET['tag'] ) : '' ); ?>">
                    <label for="tag-search">Írj be egy címkét...</label>
                </div>

                <!-- Order Select -->
                <div class="input-group">
                    <select id="order" name="order">
                        <option value="DESC" <?php selected( isset($_GET['order']) ? sanitize_text_field( $_GET['order'] ) : 'DESC', 'DESC' ); ?>>Legújabb</option>
                        <option value="ASC" <?php selected( isset($_GET['order']) ? sanitize_text_field( $_GET['order'] ) : '', 'ASC' ); ?>>Legrégebbi</option>
                    </select>
                </div>

                <!-- Layout Toggle -->
                <div class="layout-toggle">
                    <label for="layout">Megjelenítés:</label>
                    <select id="layout" name="layout">
                        <option value="list" <?php selected( isset($_GET['layout']) ? sanitize_text_field($_GET['layout']) : 'list', 'list' ); ?>>Teljes</option>
                        <option value="grid" <?php selected( isset($_GET['layout']) ? sanitize_text_field($_GET['layout']) : '', 'grid' ); ?>>Kompakt</option>
                    </select>
                </div>

                <!-- Toggle Switch (Fontos hírek elől) -->
                <div class="toggle-container">
                    <label class="toggle-switch-group">
                        <div class="toggle-switch-wrapper">
                            <?php
                            $fontos_toggle = isset($_GET['fontos_toggle']) ? ($_GET['fontos_toggle'] === '1') : true;
                            ?>
                            <input type="hidden" name="fontos_toggle" value="0">
                            <input type="checkbox" class="toggle-switch-input" name="fontos_toggle" value="1" <?php checked( $fontos_toggle ); ?>>
                            <div class="toggle-switch-track">
                                <div class="toggle-switch-thumb"></div>
                            </div>
                        </div>
                        <span class="toggle-label">Fontos hírek elől</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit">
                    Keresés és rendezés <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>

        <?php
        // Get query variables from the form.
        $order  = isset($_GET['order'])  ? sanitize_text_field( $_GET['order'] )  : 'DESC';
        $tag    = isset($_GET['tag'])    ? sanitize_text_field( $_GET['tag'] )    : '';
        // $fontos_toggle is already set above.
        $layout = isset($_GET['layout']) ? sanitize_text_field( $_GET['layout'] ) : 'list';

        // Define the current page for pagination.
        $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

        // Build the basic query arguments.
        $query_args = array_merge( $wp_query->query_vars, array(
            'order'        => $order,
            's'            => $tag,
            'tag'          => $tag ? $tag . '+hir' : 'hir',
            'tag_operator' => 'AND',
            'paged'        => $paged, // Added pagination support
        ));

        // Add date query if at least one year is provided.
        $from_year = isset($_GET['from_year']) && !empty($_GET['from_year']) ? intval($_GET['from_year']) : null;
        $to_year   = isset($_GET['to_year'])   && !empty($_GET['to_year'])   ? intval($_GET['to_year'])   : null;

        if ( $from_year || $to_year ) {
            $date_query = array();
            if ( $from_year ) {
                $date_query['after'] = array(
                    'year'  => $from_year,
                    'month' => 1,
                    'day'   => 1,
                );
            }
            if ( $to_year ) {
                $date_query['before'] = array(
                    'year'  => $to_year,
                    'month' => 12,
                    'day'   => 31,
                );
            }
            $query_args['date_query'] = array( $date_query );
        }

        // Apply the 'fontos' filter if the toggle is on.
        if ( $fontos_toggle ) {
            add_filter( 'posts_clauses', function ( $clauses ) {
                global $wpdb;
                $fontos_term = get_term_by( 'name', 'fontos', 'post_tag' );
                if ( $fontos_term ) {
                    $term_taxonomy_id = intval( $fontos_term->term_taxonomy_id );
                    $clauses['join'] .= " LEFT JOIN {$wpdb->term_relationships} AS fontos_tr 
                        ON {$wpdb->posts}.ID = fontos_tr.object_id 
                        AND fontos_tr.term_taxonomy_id = {$term_taxonomy_id}
                        AND {$wpdb->posts}.post_date >= DATE_SUB(NOW(), INTERVAL 6 WEEK)";
                    $clauses['orderby'] = "fontos_tr.object_id IS NOT NULL DESC, " . $clauses['orderby'];
                }
                return $clauses;
            } );
        }

        $custom_query = new WP_Query( $query_args );
        ?>

        <?php if ( $custom_query->have_posts() ) : ?>
            <div class="post-container <?php echo esc_attr( $layout === 'grid' ? 'grid-view' : 'list-view' ); ?>">
                <?php while ( $custom_query->have_posts() ) : $custom_query->the_post(); ?>
                    <article <?php post_class( 'post-card' ); ?>>
                        <?php if ( $fontos_toggle && has_tag( 'fontos' ) ) : ?>
                            <div class="fontos-badge">Fontos</div>
                        <?php endif; ?>

                        <header class="post-header">
                            <h2 class="post-title">
                                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                        </header>

                        <div class="post-content">
                            <?php
                            // If grid view is enabled, show the excerpt; otherwise show full content.
                            if ( 'grid' === $layout ) {
                                the_excerpt();
                            } else {
                                the_content();
                            }
                            ?>
                        </div>

                        <p class="post-meta">
                            <?php the_author(); ?> | <?php echo get_the_date(); ?>
                        </p>

                        <!-- Display Tags -->
                        <?php
                        $tags = get_the_tags();
                        if ( ! empty( $tags ) ) : ?>
                            <div class="post-tags">
                                <span class="tags-label">Címkék:</span>
                                <?php foreach ( $tags as $tag ) : ?>
                                    <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="tag">
                                        <?php echo esc_html( $tag->name ); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </article>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <?php 
                echo paginate_links( array(
                    'base'      => add_query_arg( 'paged', '%#%' ),
                    'format'    => '',
                    'current'   => $paged,
                    'total'     => $custom_query->max_num_pages,
                    'prev_text' => '<i class="fas fa-chevron-left"></i> Newer Posts',
                    'next_text' => 'Older Posts <i class="fas fa-chevron-right"></i>',
                ) );
                ?>
            </div>
        <?php else : ?>
            <p class="no-results">Nem találtunk semmit itt sajnos :(</p>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>

    </div>
</main>

<?php get_footer(); ?>