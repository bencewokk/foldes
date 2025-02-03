<?php
/**
 * Template Name: Tanáraink Grid
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php
        while (have_posts()) :
            the_post();
            get_template_part('template-parts/content', 'page');
        endwhile;
        ?>
        
        <?php
        // Define teacher data
        $teachers = [
            'fazekas' => [
                'name' => 'Fazekas Péter',
                'title' => 'Matematika Tanár',
                'bio' => '10+ év tapasztalat, OKJ tanár, kreatív módszerek',
            ],
            'csato' => [
                'name' => 'Csató László',
                'title' => 'Fizika Tanár',
                'bio' => 'PhD, egyetemi oktató, versenyfelkészítő',
            ],
            'pilz' => [
                'name' => 'Pilz Katalin',
                'title' => 'Biológia Tanár',
                'bio' => 'Természettudományok szakértő, projektalapú oktatás',
            ],
            'chalu' => [
                'name' => 'Chalu Péter',
                'title' => 'Informatika Tanár',
                'bio' => 'Full-stack fejlesztő, programozási versenyek szervezője',
            ],
            'lorincz' => [
                'name' => 'Lőrincz Anna',
                'title' => 'Magyar Nyelv és Irodalom Tanár',
                'bio' => 'Író, költő, kreatív írás szakértő',
            ],
            'kondi' => [
                'name' => 'Kondi Éva',
                'title' => 'Angol Nyelv Tanár',
                'bio' => 'Cambridge vizsgakészítő, anyanyelvi szintű angol',
            ],
        ];

        if (!empty($teachers)) :
        ?>
            <div class="teachers-grid">
                <?php foreach ($teachers as $slug => $teacher) : ?>
                    <article class="teacher-card">
                        <div class="image-container">
                            <img src="<?php echo esc_url(get_template_directory_uri() . "/tanaraink/tanarok/{$slug}.jpg"); ?>" 
                                 alt="<?php echo esc_attr($teacher['name']); ?>">
                            <div class="teacher-overlay">
                                <div class="teacher-overlay-content">
                                    <h4><?php echo esc_html($teacher['title']); ?></h4>
                                    <ul>
                                        <li><?php echo esc_html($teacher['bio']); ?></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="teacher-info">
                            <h3 class="teacher-name"><?php echo esc_html($teacher['name']); ?></h3>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
?>