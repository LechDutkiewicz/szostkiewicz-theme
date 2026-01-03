<?php
// Wymusza użycie Elementor header/footer
if (!defined('ABSPATH')) {
    exit;
}

// Sprawdź czy Elementor ma header/footer
$elementor_header = get_option('elementor_header_template_id');
$elementor_footer = get_option('elementor_footer_template_id');

if ($elementor_header || $elementor_footer) {
    // Użyj canvas template
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
                    <?php while (have_posts()) : the_post(); 
                                    // opisy
                        $dimensions = get_field('o_obrazie')['wymiary_obrazu'];
                        $price = get_field('o_obrazie')['cena_obrazu'];
                        $description = get_field('o_obrazie')['opis_obrazu'];
                        $first_image = $gallery ? $gallery[0] : null;
                        ?>
                        <a class="painting-item" href="<?php the_permalink(); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium', array("class" => "shadow-black")); ?>
                            <?php endif; ?>
                            <h3 class="h6"><?php the_title(); ?></h3>
                            <?php if ($dimensions) : ?>
                                <span class="dimensions"><?php echo $dimensions; ?></span>
                            <?php endif; ?>
                            <?php if ($price) : ?>
                                <span class="price"><?php echo $price; ?></span>
                            <?php endif; ?>
                            <?php if ($description) : ?>
                                <div class="description"><?php echo $description; ?></div>
                            <?php endif; ?>
                        </a>
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
if ($elementor_header || $elementor_footer) {
    get_footer('elementor');
} else {
    get_footer();
}
?>