<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header id="site-header">
    <div class="header-container">
    
        <div class="header-left">
             <a href="<?php echo home_url('/'); ?>" class="home-link">
                <img src="<?php echo get_template_directory_uri(); ?>/banner.png" alt="School Banner" class="header-banner">
            </a>
            <a href="<?php echo home_url('/'); ?>" class="home-link">
                <h1 class="school-name">Földes Ferenc Gimnázium</h1>
            </a>
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
  document.addEventListener('DOMContentLoaded', function () {
    const accessibilityPanel = document.querySelector('.accessibility-panel');
    const accessibilityButton = document.querySelector('.accessibility-button');
    const accessibilityOptions = document.querySelector('.accessibility-options');

    // Toggle panel visibility
    accessibilityButton.addEventListener('click', function (e) {
      accessibilityPanel.classList.toggle('active');
      e.stopPropagation(); // Prevent immediate closing when clicking the button
    });

    // Close panel when clicking outside
    document.addEventListener('click', function (e) {
      if (!accessibilityPanel.contains(e.target)) {
        accessibilityPanel.classList.remove('active');
      }
    });

    // Close panel on ESC key
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        accessibilityPanel.classList.remove('active');
      }
    });

    // Theme toggle logic
    const toggleTheme = document.getElementById('toggleTheme');
    toggleTheme.addEventListener('change', function () {
      document.body.classList.toggle('theme-two', this.checked);
      localStorage.setItem('high-contrast', this.checked ? 'on' : 'off');
    });

    // Load saved theme preference
    const savedTheme = localStorage.getItem('high-contrast');
    if (savedTheme === 'on') {
      toggleTheme.checked = true;
      document.body.classList.add('theme-two');
    }
  });

  document.addEventListener('DOMContentLoaded', function () {
    // Font Toggle Logic
    const toggleFont = document.getElementById('toggleFont');
    toggleFont.addEventListener('change', function () {
      document.body.classList.toggle('dyslexia-font', this.checked);
      localStorage.setItem('dyslexia-font', this.checked ? 'on' : 'off');
    });

    // Load saved font preference
    const savedFont = localStorage.getItem('dyslexia-font');
    if (savedFont === 'on') {
      toggleFont.checked = true;
      document.body.classList.add('dyslexia-font');
    }
  });
</script>


<div class="accessibility-panel">
  <button class="accessibility-button" aria-label="Accessibility Options">
    <i class="fas fa-universal-access"></i>
  </button>
  <div class="accessibility-options">
    <!-- Theme Toggle -->
    <div class="theme-toggle">
      <input type="checkbox" id="toggleTheme" class="a11y-toggle-input">
      <label class="a11y-toggle-track" for="toggleTheme">
        <span class="a11y-toggle-thumb"></span>
      </label>
      <label for="toggleTheme" class="a11y-toggle-label">Nagy kontraszt</label>
    </div>

    <!-- Font Toggle -->
    <div class="font-toggle">
      <input type="checkbox" id="toggleFont" class="a11y-toggle-input">
      <label class="a11y-toggle-track" for="toggleFont">
        <span class="a11y-toggle-thumb"></span>
      </label>
      <label for="toggleFont" class="a11y-toggle-label">Dyslexia-barát szöveg</label>
    </div>
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
  // Theme Toggle Logic
  const toggleTheme = document.getElementById('toggleTheme');
  toggleTheme.checked = false;
  document.body.classList.remove('theme-two');

  toggleTheme.addEventListener('change', function () {
    document.body.classList.toggle('theme-two', this.checked);
    localStorage.setItem('high-contrast', this.checked ? 'on' : 'off');
  });

  const savedTheme = localStorage.getItem('high-contrast');
  if (savedTheme === 'on') {
    toggleTheme.checked = true;
    document.body.classList.add('theme-two');
  }

  // Mobile Menu Toggle Logic
  const mobileToggle = document.querySelector('.mobile-nav-toggle');
  const body = document.body;

  mobileToggle.addEventListener('click', function () {
    const isExpanded = this.getAttribute('aria-expanded') === 'true';
    this.setAttribute('aria-expanded', !isExpanded);
    body.classList.toggle('nav-active');
  });

  // Close menu when clicking outside
  document.addEventListener('click', function (e) {
    if (!e.target.closest('.nav-container') && !e.target.closest('.mobile-nav-toggle')) {
      mobileToggle.setAttribute('aria-expanded', 'false');
      body.classList.remove('nav-active');
    }
  });

  // Close menu on ESC key
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      mobileToggle.setAttribute('aria-expanded', 'false');
      body.classList.remove('nav-active');
    }
  });

  // Lower Navigation Collapsible Sections Logic
  const lowerNavHeaders = document.querySelectorAll('#lower-navigation .nav-section h3');
  lowerNavHeaders.forEach(header => {
    header.setAttribute('role', 'button');
    header.setAttribute('tabindex', '0');
    header.setAttribute('aria-expanded', 'false');

    header.addEventListener('click', function () {
      const list = this.nextElementSibling;
      const isExpanded = list.classList.toggle('active');
      this.setAttribute('aria-expanded', isExpanded);
    });

    header.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        header.click();
      }
    });
  });
});
</script>



<div class="nav-container">
    <nav id="main-navigation">
    <div class="toggle-container">
        <ul class="custom-menu">
            <li><a href="<?php echo esc_url(home_url('/')); ?>">Otthon</a></li>
            <li><a href="<?php echo esc_url(home_url('/iskolankrol')); ?>">Iskolánkról</a></li>
            <li><a href="<?php echo esc_url(home_url('/beiratkozas')); ?>">Beiratkozás</a></li>
            <li><a href="<?php echo esc_url(home_url('/esemenyek')); ?>">Események</a></li>
            <li><a href="<?php echo esc_url(home_url('/tanaraink')); ?>">Tanáraink</a></li>
            <li><a href="<?php echo esc_url(home_url('/tablok')); ?>">Tablók</a></li>
            <li><a href="<?php echo esc_url(home_url('/versenyek')); ?>">Versenyek</a></li>
            <li><a href="<?php echo esc_url(home_url('/kapcsolat')); ?>">Kapcsolat</a></li>
            <li><a href="https://ffg.e-kreta.hu>">Enapló</a></li>


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
                    <li><a href="/esemenyek">Menza</a></li>
                    <li><a href="/esemenyek">Tehetség gondozás</a></li>
                    <li><a href="/galeria">Szülői Munkaközösség</a></li>
                    <li><a href="/galeria">placeholder</a></li>

                </ul>
            </div>
            
            <div class="nav-section">
                <h3>Diákoknak</h3>
                <ul>
                    <li><a href="/csengetes">Csengetés</a></li>
                    <li><a href="/szakkorok">Szakkörök</a></li>
                    <li><a href="/galeria">Sport</a></li>
                    <li><a href="/galeria">Továbbtanulás</a></li>
                </ul>
            </div>
            
            <div class="nav-section">
                <h3>Információk</h3>
                <ul>
                    <li><a href="/kapcsolat">Öregdiák</a></li>
                    <li><a href="/galeria">DÖK</a></li>
                    <li><a href="/galeria">Alapitványok</a></li>
                    <li><a href="/galeria">Kihez fordulhatok?</a></li>

                </ul>
            </div>
        </div>
    </nav>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // Mobile Menu Toggle
  const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
  const mobileNav = document.querySelector('.mobile-nav');
  const mobileOverlay = document.querySelector('.mobile-overlay');
  const body = document.body;
  
  if (mobileMenuToggle) {
    mobileMenuToggle.addEventListener('click', function() {
      mobileNav.classList.toggle('active');
      mobileOverlay.classList.toggle('active');
      body.classList.toggle('mobile-menu-open');
      
      // Animate the hamburger icon
      const bars = this.querySelectorAll('.bar');
      bars.forEach(bar => bar.classList.toggle('active'));
    });
  }
  
  // Close menu when clicking on overlay
  if (mobileOverlay) {
    mobileOverlay.addEventListener('click', function() {
      mobileNav.classList.remove('active');
      mobileOverlay.classList.remove('active');
      body.classList.remove('mobile-menu-open');
      
      const bars = mobileMenuToggle.querySelectorAll('.bar');
      bars.forEach(bar => bar.classList.remove('active'));
    });
  }
  
  // Mobile Nav Section Toggle
  const mobileNavSections = document.querySelectorAll('.mobile-nav-section h3');
  mobileNavSections.forEach(section => {
    section.addEventListener('click', function() {
      this.classList.toggle('active');
    });
  });
  
  // Theme Toggle Synchronization
  const desktopToggleTheme = document.getElementById('toggleTheme');
  const mobileToggleTheme = document.getElementById('mobileToggleTheme');
  
  if (desktopToggleTheme && mobileToggleTheme) {
    // Sync mobile toggle to desktop toggle initial state
    mobileToggleTheme.checked = desktopToggleTheme.checked;
    
    // Keep toggles in sync
    desktopToggleTheme.addEventListener('change', function() {
      mobileToggleTheme.checked = this.checked;
    });
    
    mobileToggleTheme.addEventListener('change', function() {
      desktopToggleTheme.checked = this.checked;
      document.body.classList.toggle('theme-two', this.checked);
      localStorage.setItem('high-contrast', this.checked ? 'on' : 'off');
    });
  }
  
  // Add animation styles for menu toggle button
  const style = document.createElement('style');
  style.textContent = `
    .mobile-menu-toggle .bar.active:nth-child(1) {
      transform: rotate(45deg) translate(5px, 5px);
    }
    
    .mobile-menu-toggle .bar.active:nth-child(2) {
      opacity: 0;
    }
    
    .mobile-menu-toggle .bar.active:nth-child(3) {
      transform: rotate(-45deg) translate(7px, -6px);
    }
  `;
  document.head.appendChild(style);
});
</script>
<!-- Mobile Menu Toggle Button - Add this right after the header -->
<button class="mobile-menu-toggle" aria-label="Toggle mobile menu">
  <span class="bar"></span>
  <span class="bar"></span>
  <span class="bar"></span>
</button>

<!-- Mobile Navigation Overlay -->
<div class="mobile-overlay"></div>

<!-- Mobile Navigation Menu -->
<nav class="mobile-nav">
  <div class="mobile-nav-header">
    <div class="toggle-switch-group">
      <div class="toggle-switch-wrapper">
        <input type="checkbox" id="mobileToggleTheme" class="toggle-switch-input">
        <label class="toggle-switch-track" for="mobileToggleTheme">
          <span class="toggle-switch-thumb"></span>
        </label>
      </div>
    </div>
    <label style="color: white; font-weight: bold; font-size: 0.95rem; margin-left: 10px;">Nagy kontraszt</label>
  </div>
  
  <div class="mobile-nav-content">
    <!-- Main Menu Section -->
    <div class="mobile-nav-section">
      <h3>Főmenü</h3>
      <ul>
        <li><a href="<?php echo esc_url(home_url('/')); ?>">Otthon</a></li>
        <li><a href="<?php echo esc_url(home_url('/iskolankrol')); ?>">Iskolánkról</a></li>
        <li><a href="<?php echo esc_url(home_url('/tanaraink')); ?>">Tanáraink</a></li>
        <li><a href="<?php echo esc_url(home_url('/tablok')); ?>">Tablók</a></li>
        <li><a href="<?php echo esc_url(home_url('/versenyek')); ?>">Versenyek</a></li>
      </ul>
    </div>
    
    <!-- Parents Section -->
    <div class="mobile-nav-section">
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
    
    <!-- Students Section -->
    <div class="mobile-nav-section">
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
    
    <!-- Information Section -->
    <div class="mobile-nav-section">
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
    
    <!-- Contact Section - show contact info that was hidden from header -->
    <div class="mobile-contact">
      <div>E-mail: <a href="mailto:ffg@ffg.hu">ffg@ffg.hu</a></div>
      <div>Titkárság: <a href="tel:+3646508459">+36 (46) 508-459</a></div>
      <div class="gtranslate_wrapper_mobile">
        <?php echo do_shortcode('[gtranslate]'); ?>
      </div>
    </div>
  </div>
</nav>

<?php wp_footer(); ?>
</body>
</html>
