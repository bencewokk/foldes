<?php
/*
Template Name: Tanaraink Custom Page
*/
get_header();  // Include the header
?>

<div class="container">
    <article>
        <div class="post-header">
            <h1 class="post-title"><?php the_title(); ?></h1>
        </div>
        <div class="post-content">
            <div class="gallery-flex">
                <div class="gallery-item">
                    <img src="<?php echo get_template_directory_uri(); ?>/tanarok/fazekas.jpg" alt="Fazekas">
                    <div class="gallery-caption">
                        Fazekas
                        <button class="gallery-plus" aria-label="Increment counter">+</button>
                        <span class="gallery-counter">0</span>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="<?php echo get_template_directory_uri(); ?>/tanarok/csato.jpg" alt="Csato">
                    <div class="gallery-caption">
                        Csato
                        <button class="gallery-plus" aria-label="Increment counter">+</button>
                        <span class="gallery-counter">0</span>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="<?php echo get_template_directory_uri(); ?>/tanarok/pilz.jpg" alt="Pilz">
                    <div class="gallery-caption">
                        Pilz
                        <button class="gallery-plus" aria-label="Increment counter">+</button>
                        <span class="gallery-counter">0</span>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="<?php echo get_template_directory_uri(); ?>/tanarok/chalu.jpg" alt="Chalu">
                    <div class="gallery-caption">
                        Chalu
                        <button class="gallery-plus" aria-label="Increment counter">+</button>
                        <span class="gallery-counter">0</span>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="<?php echo get_template_directory_uri(); ?>/tanarok/lorincz.jpg" alt="Lorincz">
                    <div class="gallery-caption">
                        Lorincz
                        <button class="gallery-plus" aria-label="Increment counter">+</button>
                        <span class="gallery-counter">0</span>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="<?php echo get_template_directory_uri(); ?>/tanarok/kondi.jpg" alt="Kondi">
                    <div class="gallery-caption">
                        Kondi
                        <button class="gallery-plus" aria-label="Increment counter">+</button>
                        <span class="gallery-counter">0</span>
                    </div>
                </div>
            </div>
        </div>
    </article>
</div>

<!-- JavaScript to increment the counter when the plus button is clicked -->
<script>
document.addEventListener('DOMContentLoaded', function(){
    const plusButtons = document.querySelectorAll('.gallery-plus');
    plusButtons.forEach(function(button) {
        button.addEventListener('click', function(){
            // Find the next sibling element which is the counter
            const counterElem = button.nextElementSibling;
            let count = parseInt(counterElem.textContent, 10);
            count++;
            counterElem.textContent = count;
        });
    });
});
</script>

<?php get_footer(); // Include the footer ?>
