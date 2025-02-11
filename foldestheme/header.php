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
    </div>
</header>

<!-- Wrap both navs in a container -->
<div class="nav-container">
    <!-- Your existing top nav -->
    <nav id="main-navigation">
        <ul class="custom-menu">
            <li><a href="<?php echo esc_url(home_url('/')); ?>">Otthon</a></li>
            <li><a href="<?php echo esc_url(home_url('/iskolankrol')); ?>">Iskolánkról</a></li>
            <li><a href="<?php echo esc_url(home_url('/tanaraink')); ?>">Tanáraink</a></li>
            <li><a href="<?php echo esc_url(home_url('/tablok')); ?>">Tablók</a></li>
            <li><a href="<?php echo esc_url(home_url('/versenyek')); ?>">Versenyek</a></li>
            <li><a href="<?php echo esc_url(home_url('/szuloknek')); ?>">Szülőknek</a></li>
        </ul>
        <div class="gtranslate_wrapper">
            <?php echo do_shortcode('[gtranslate]'); ?> 
        </div>
        <button id="lower-nav-toggle" aria-expanded="false" aria-label="Toggle lower navigation">
            <svg viewBox="0 0 24 24">
                <path d="M7 10l5 5 5-5z"/>
            </svg>
        </button>
    </nav>
    
    <!-- Lower nav -->
    <nav id="lower-navigation">
        <div class="lower-nav-content">
            <div class="nav-section">
                <h3>Iskolai Élet</h3>
                <ul>
                    <li><a href="/hirek">Hírek</a></li>
                    <li><a href="/esemenyek">Események</a></li>
                    <li><a href="/galeria">Galéria</a></li>
                </ul>
            </div>
            
            <div class="nav-section">
                <h3>Oktatás</h3>
                <ul>
                    <li><a href="/tanterv">Tanterv</a></li>
                    <li><a href="/orarend">Órarend</a></li>
                    <li><a href="/szakkörök">Szakkörök</a></li>
                </ul>
            </div>
            
            <div class="nav-section">
                <h3>Információk</h3>
                <ul>
                    <li><a href="/dokumentumok">Dokumentumok</a></li>
                    <li><a href="/letoltesek">Letöltések</a></li>
                    <li><a href="/kapcsolat">Kapcsolat</a></li>
                </ul>
            </div>
        </div>
    </nav>
</div>

<?php wp_footer(); ?>
</body>
</html>