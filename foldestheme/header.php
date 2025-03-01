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
            <p class="school-motto">"Jót, s jól."</p>
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


<!-- Accessibility Panel -->
<div class="accessibility-panel">
  <button class="back-to-top" aria-label="Back to top">
    <i class="fas fa-arrow-up"></i>
  </button>
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



<style>
.back-to-top {
  width: 50px;
  height: 50px;
  border: none;
  border-radius: 50%;
  background-color: #007bff;
  color: white;
  cursor: pointer;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 5px rgba(0,0,0,0.3);
  order: 1; /* Positions above the accessibility button */
}


.back-to-top.show {
  opacity: 1;
  visibility: visible;
}

.back-to-top:hover {
  background-color: #0056b3;
}
</style>

<script>
// Existing JavaScript remains the same
const backToTopButton = document.querySelector('.back-to-top');

window.addEventListener('scroll', () => {
  if (window.pageYOffset > 300) {
    backToTopButton.classList.add('show');
  } else {
    backToTopButton.classList.remove('show');
  }
});

backToTopButton.addEventListener('click', () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
});
</script>


<!-- Mobile Menu Toggle Button -->
<button class="mobile-menu-toggle" aria-label="Toggle mobile menu">
  <span class="bar"></span>
  <span class="bar"></span>
  <span class="bar"></span>
</button>

<!-- Mobile Navigation Overlay -->
<div class="mobile-overlay"></div>

<!-- Desktop Navigation -->
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
        <li class="has-dropdown">
            <a href="<?php echo esc_url(home_url('/konyvtar')); ?>">Könyvtár</a>
            <ul class="dropdown-menu">
                <li>
                    <a href="<?php echo esc_url('https://opac.rfmlib.hu/WebPac_SULI/CorvinaWeb?action=advancedsearchpage&CLOC=M106'); ?>" target="_blank">
                        <i class="fas fa-external-link-alt"></i> Katalógus
                    </a>
                </li>
            </ul>
        </li>
        <li><a href="<?php echo esc_url(home_url('/kapcsolat')); ?>">Kapcsolat</a></li>
        <li>
            <a href="<?php echo esc_url('https://ffg.e-kreta.hu'); ?>" target="_blank">
                <i class="fas fa-external-link-alt"></i> Enapló
            </a>
        </li>
        <li><a href="<?php echo esc_url('/?s'); ?>">Keresés</a></li>
      </ul>
  </div>

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

<!-- Mobile Navigation Menu -->
<nav class="mobile-nav">
  <div class="mobile-nav-header">
    <label style="color: white; font-weight: bold; font-size: 0.95rem; margin-left: 10px;">Földes</label>
  </div>
  
  <div class="mobile-nav-content">
    <!-- Main Menu Section -->
    <div class="mobile-nav-section">
      <h3>Főmenü</h3>
      <ul>
      <li><a href="<?php echo esc_url(home_url('/')); ?>">Otthon</a></li>
            <li><a href="<?php echo esc_url(home_url('/iskolankrol')); ?>">Iskolánkról</a></li>
            <li><a href="<?php echo esc_url(home_url('/beiratkozas')); ?>">Beiratkozás</a></li>
            <li><a href="<?php echo esc_url(home_url('/esemenyek')); ?>">Események</a></li>
            <li><a href="<?php echo esc_url(home_url('/tanaraink')); ?>">Tanáraink</a></li>
            <li><a href="<?php echo esc_url(home_url('/tablok')); ?>">Tablók</a></li>
            <li><a href="<?php echo esc_url(home_url('/versenyek')); ?>">Versenyek</a></li>
            <li class="has-dropdown">
                <a href="<?php echo esc_url(home_url('/konyvtar')); ?>">Könyvtár</a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo esc_url('https://opac.rfmlib.hu/WebPac_SULI/CorvinaWeb?action=advancedsearchpage&CLOC=M106'); ?>">Katalógus</a></li>
                </ul>
            </li>
            <li><a href="<?php echo esc_url(home_url('/kapcsolat')); ?>">Kapcsolat</a></li>
            <li><a href="<?php echo esc_url('https://ffg.e-kreta.hu'); ?>">Enapló</a></li>
            <li><a href="<?php echo esc_url('/search.php'); ?>">Keresés</a></li>

      </ul>
    </div>
    
    <!-- Parents Section -->
    <div class="mobile-nav-section">
      <h3>Szülőknek</h3>
      <ul>
        <li><a href="/esemenyek">Menza</a></li>
        <li><a href="/esemenyek">Tehetség gondozás</a></li>
        <li><a href="/galeria">Szülői Munkaközösség</a></li>
        <li><a href="/galeria">placeholder</a></li>
      </ul>
    </div>
    
    <!-- Students Section -->
    <div class="mobile-nav-section">
      <h3>Diákoknak</h3>
      <ul>
        <li><a href="/csengetes">Csengetés</a></li>
        <li><a href="/szakkorok">Szakkörök</a></li>
        <li><a href="/galeria">Sport</a></li>
        <li><a href="/galeria">Továbbtanulás</a></li>
      </ul>
    </div>
    
    <!-- Information Section -->
    <div class="mobile-nav-section">
      <h3>Információk</h3>
      <ul>
        <li><a href="/kapcsolat">Öregdiák</a></li>
        <li><a href="/galeria">DÖK</a></li>
        <li><a href="/galeria">Alapitványok</a></li>
        <li><a href="/galeria">Kihez fordulhatok?</a></li>
      </ul>
    </div>
    
    <!-- Contact Section -->
    <div class="mobile-contact">
      <div>E-mail: <a href="mailto:ffg@ffg.hu">ffg@ffg.hu</a></div>
      <div>Titkárság: <a href="tel:+3646508459">+36 (46) 508-459</a></div>
      <div class="gtranslate_wrapper_mobile">
        <?php echo do_shortcode('[gtranslate]'); ?>
      </div>
    </div>
  </div>
</nav>

<!-- Consolidated JavaScript for all functionality -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  // --- Accessibility Panel ---
  const accessibilityPanel = document.querySelector('.accessibility-panel');
  const accessibilityButton = document.querySelector('.accessibility-button');
  
  // Toggle panel visibility
  accessibilityButton.addEventListener('click', function (e) {
    accessibilityPanel.classList.toggle('active');
    e.stopPropagation();
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
  
  // --- Theme Toggle ---
  const toggleTheme = document.getElementById('toggleTheme');
  const mobileToggleTheme = document.getElementById('mobileToggleTheme');
  
  // Desktop theme toggle
  toggleTheme.addEventListener('change', function () {
    document.body.classList.toggle('theme-two', this.checked);
    localStorage.setItem('high-contrast', this.checked ? 'on' : 'off');
    
    // Keep mobile toggle in sync
    if (mobileToggleTheme) {
      mobileToggleTheme.checked = this.checked;
    }
  });
  
  // Mobile theme toggle
  if (mobileToggleTheme) {
    mobileToggleTheme.addEventListener('change', function () {
      document.body.classList.toggle('theme-two', this.checked);
      localStorage.setItem('high-contrast', this.checked ? 'on' : 'off');
      toggleTheme.checked = this.checked;
    });
  }
  
  // Load saved theme preference
  const savedTheme = localStorage.getItem('high-contrast');
  if (savedTheme === 'on') {
    toggleTheme.checked = true;
    if (mobileToggleTheme) mobileToggleTheme.checked = true;
    document.body.classList.add('theme-two');
  }
  
  // --- Dyslexia Font Toggle ---
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
  
  // --- Mobile Menu Toggle ---
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
  
  // --- Mobile Nav Section Toggle ---
  const mobileNavSections = document.querySelectorAll('.mobile-nav-section h3');
  mobileNavSections.forEach(section => {
    section.addEventListener('click', function() {
      this.classList.toggle('active');
    });
  });
  
  // --- Lower Navigation Collapsible Sections ---
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

<?php wp_footer(); ?>
</body>
</html>