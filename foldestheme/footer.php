<!-- Footer -->
<footer id="site-footer">
    <div class="footer-content container">
        <!-- Footer Widgets -->
        <div class="footer-widgets">
            <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                <div class="footer-widget-area">
                    <?php dynamic_sidebar( 'footer-1' ); ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Footer Copyright -->
        <div class="footer-copyright">
            <p>&copy; 2025 Földes Ferenc Gimnázium | Created by Balabás Bence</p>
        </div>
    </div>
</footer>
<!-- End Footer -->
