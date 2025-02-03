<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php bloginfo( 'name' ); ?> - <?php bloginfo( 'description' ); ?></title>
    
    <!-- Link to your theme's stylesheet -->
    <link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>">

    <?php wp_head(); ?> <!-- WordPress hook to add additional elements like scripts and styles -->
</head>
<body <?php body_class(); ?>>

<header id="site-header">
    <div class="container">
        <!-- Navigation Menu -->

        <img src="<?php echo get_template_directory_uri(); ?>/banner.png" alt="Banner" class="header-banner">
        <div class="est-badge">INCEPTA 1953</div>

        <nav id="main-navigation">
            

            <ul class="custom-menu">
                <li><a href="<?php echo esc_url(home_url('/')); ?>">Otthon</a></li>
                <li><a href="<?php echo esc_url(home_url('/iskolankrol')); ?>">Iskolánkról</a></li>
                <li><a href="<?php echo esc_url(home_url('/programok')); ?>" class="tab-link active">Programok</a></li>
                <li><a href="<?php echo esc_url(home_url('/diakoknak')); ?>" class="tab-link">Diákoknak</a></li>
                <li><a href="<?php echo esc_url(home_url('/szuloknek')); ?>" class="tab-link">Szülőknek</a></li>
                <li><a href="<?php echo esc_url(home_url('/tanaraink')); ?>" class="tab-link">Tanáraink</a></li>
            </ul>

            
        </nav>
    </div>
</header>

<!-- The rest of your page content will go here -->

<?php wp_footer(); ?> <!-- WordPress hook to add scripts before the closing </body> tag -->
</body>
</html>
