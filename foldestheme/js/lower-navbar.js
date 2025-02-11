// Create this as a new file: lower-navbar.js
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('lower-nav-toggle');
    const lowerNav = document.getElementById('lower-navigation');
    
    toggleButton.addEventListener('click', function() {
        const isExpanded = toggleButton.getAttribute('aria-expanded') === 'true';
        toggleButton.setAttribute('aria-expanded', !isExpanded);
        lowerNav.classList.toggle('expanded');
        
        // Rotate the arrow icon
        toggleButton.classList.toggle('rotated');
    });
});