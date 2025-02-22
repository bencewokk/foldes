<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header id="site-header">
    <div class="header-container">
    <button class="mobile-nav-toggle" aria-label="Mobile Menu" aria-expanded="false">
      <span class="hamburger"></span>
    </button>
        <div class="header-left">
             <a href="<?php echo home_url('/'); ?>" class="home-link">
                <img src="<?php echo get_template_directory_uri(); ?>/banner.png" alt="School Banner" class="header-banner">
            </a>
            <a href="<?php echo home_url('/'); ?>" class="home-link">
                <h1 class="school-name">Földes Ferenc Gimnázium</h1>
            </a
            <p class="school-motto">“Jót, s jól.”</p>
        </div>
        <div class="header-right">
            <div class="header-contact">
                <div class="contact-item">E-mail: <a href="mailto:ffg@ffg.hu">ffg@ffg.hu</a></div>
                <div class="contact-item">Titkárság: <a href="tel:+3646508459">+36 (46) 508-459</a></div>
            </div>
            <div class="gtranslate_wrapper">
                <?php echo do_shortcode('[gtranslate]'); ?>
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const toggleTheme = document.getElementById('toggleTheme');
  
  toggleTheme.checked = false;
  document.body.classList.remove('theme-two');

  toggleTheme.addEventListener('change', function() {
    document.body.classList.toggle('theme-two', this.checked);
    localStorage.setItem('high-contrast', this.checked ? 'on' : 'off');
  });

  const savedTheme = localStorage.getItem('high-contrast');
  if (savedTheme === 'on') {
    toggleTheme.checked = true;
    document.body.classList.add('theme-two');
  }
});

document.addEventListener('DOMContentLoaded', function() {
  const mobileToggle = document.querySelector('.mobile-nav-toggle');
  const body = document.body;
  
  mobileToggle.addEventListener('click', function() {
    const isExpanded = this.getAttribute('aria-expanded') === 'true';
    this.setAttribute('aria-expanded', !isExpanded);
    body.classList.toggle('nav-active');
  });
  
  // Close menu when clicking outside
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.nav-container') && !e.target.closest('.mobile-nav-toggle')) {
      mobileToggle.setAttribute('aria-expanded', 'false');
      body.classList.remove('nav-active');
    }
  });
  
  // Close menu on ESC key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      mobileToggle.setAttribute('aria-expanded', 'false');
      body.classList.remove('nav-active');
    }
  });
});
</script>



<div class="nav-container">
    <nav id="main-navigation">
    <div class="toggle-container">
        <div class="toggle-switch-group">
                <div class="toggle-switch-wrapper">
                <input type="checkbox" id="toggleTheme" class="toggle-switch-input">
                <label class="toggle-switch-track" for="toggleTheme">
                    <span class="toggle-switch-thumb"></span>
                </label>
                </div>
            </div>
        </div>

        <label style="color: var(--secondary-color); font-weight: bold;font-size: 0.95rem;">Nagy kontraszt</label>
        <ul class="custom-menu">
            <li><a href="<?php echo esc_url(home_url('/')); ?>">Otthon</a></li>
            <li><a href="<?php echo esc_url(home_url('/iskolankrol')); ?>">Iskolánkról</a></li>
            <li><a href="<?php echo esc_url(home_url('/tanaraink')); ?>">Tanáraink</a></li>
            <li><a href="<?php echo esc_url(home_url('/tablok')); ?>">Tablók</a></li>
            <li><a href="<?php echo esc_url(home_url('/versenyek')); ?>">Versenyek</a></li>
        </ul>
        
    </nav>
    
    <nav id="lower-navigation" class="secondary-nav">
        <button id="lower-nav-toggle" aria-expanded="false" aria-controls="lower-navigation">
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
        </button>
        <div class="secondary-nav__content">
            <div class="nav-section">
                <h3>Szülőknek</h3>
                <ul>
                    <li><a href="/hirek">Hírek</a></li>
                    <li><a href="/esemenyek">Menza</a></li>
                    <li><a href="/esemenyek">Tehetség gondozás</a></li>
                    <li><a href="/esemenyek">Beiratkozás</a></li>
                    <li><a href="/galeria">Szülői Munkaközösség</a></li>
                    <li><a href="/galeria">Kihez fordulhatok?</a></li>
                </ul>
            </div>
            
            <div class="nav-section">
                <h3>Diákoknak</h3>
                <ul>
                    <li><a href="/csengetes">Csengetés</a></li>
                    <li><a href="/orarend">Órarend</a></li>
                    <li><a href="/szakkorok">Szakkörök</a></li>
                    <li><a href="/galeria">Sport</a></li>
                    <li><a href="/galeria">Továbbtanulás</a></li>
                    <li><a href="/galeria">Kihez fordulhatok?</a></li>
                </ul>
            </div>
            
            <div class="nav-section">
                <h3>Információk</h3>
                <ul>
                    <li><a href="/dokumentumok">Enapló</a></li>
                    <li><a href="/kapcsolat">Kapcsolat</a></li>
                    <li><a href="/kapcsolat">Események</a></li>
                    <li><a href="/kapcsolat">Öregdiák</a></li>
                    <li><a href="/galeria">DÖK</a></li>
                    <li><a href="/galeria">Alapitványok</a></li>
                </ul>
            </div>
        </div>
    </nav>
</div>

<?php wp_footer(); ?>
</body>
</html>
