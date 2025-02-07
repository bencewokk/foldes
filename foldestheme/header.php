<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header id="site-header">
    <div class="container">
        <img src="<?php echo get_template_directory_uri(); ?>/banner.png" alt="Banner" class="header-banner">
        <div class="est-badge">INCEPTA 1953</div>

        <nav id="main-navigation">
            <ul class="custom-menu">
                <li><a href="<?php echo esc_url(home_url('/')); ?>">Otthon</a></li>
                <li><a href="<?php echo esc_url(home_url('/iskolankrol')); ?>">Iskolánkról</a></li>
                <li><a href="<?php echo esc_url(home_url('/diakoknak')); ?>">Diákoknak</a></li>
                <li><a href="<?php echo esc_url(home_url('/szuloknek')); ?>">Szülőknek</a></li>
                <li><a href="<?php echo esc_url(home_url('/tanaraink')); ?>">Tanáraink</a></li>
                <li><a href="<?php echo esc_url(home_url('/osztalyaink')); ?>">Osztályaink</a></li>
                <li><a href="<?php echo esc_url(home_url('/tablok')); ?>">Tablók</a></li>
                <div class="gtranslate_wrapper">
                    <?php echo do_shortcode('[gtranslate]'); ?> 
                </div>
        
            </ul>
        </nav>
        
        
    </div>
</header>

<?php wp_footer(); ?>
</body>
</html>