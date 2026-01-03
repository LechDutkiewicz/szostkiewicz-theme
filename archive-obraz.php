<?php
/**
 * Archive template for Obraz post type
 *
 * @package Hello_Theme_Child
 */

if (!defined('ABSPATH')) {
    exit;
}

$use_elementor = is_using_elementor_header_footer();

if ($use_elementor) {
    get_header('elementor');
} else {
    get_header();
}
?>

<main <?php post_class('main-content'); ?>>
    <div class="elementor-element section-md e-flex e-con-boxed e-con" data-element_type="container">
        <div class="e-con-inner">

            <header class="archive-header">
                <h1 class="archive-title">Galeria</h1>
            </header>

            <?php if (have_posts()) : ?>
                <div class="featured-paintings archive-paintings">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php render_painting_card(); ?>
                    <?php endwhile; ?>
                </div>

                <?php
                the_posts_pagination(array(
                    'mid_size' => 2,
                    'prev_text' => '« Poprzednie',
                    'next_text' => 'Następne »',
                ));
                ?>

            <?php else : ?>
                <p>Nie znaleziono żadnych obrazów.</p>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
if ($use_elementor) {
    get_footer('elementor');
} else {
    get_footer();
}
?>