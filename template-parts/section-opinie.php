<?php
/**
 * Template part for displaying testimonials section
 *
 * @package Hello_Theme_Child
 */

if (!defined('ABSPATH')) {
    exit;
}

$opinie = new WP_Query([
    'post_type' => 'opinia',
    'posts_per_page' => 10,
    'orderby' => 'menu_order',
    'order' => 'ASC',
]);

if (!$opinie->have_posts()) {
    return;
}
?>

<section class="opinie-section">
    <div class="opinie-section__background"></div>
    <div class="opinie-section__container">
        <h2 class="opinie-section__title">Sztuka, która przemawia do duszy</h2>

        <div class="swiper opinie-swiper">
            <div class="swiper-wrapper">
                <?php while ($opinie->have_posts()) : $opinie->the_post();
                    $autor_nazwa = get_field('autor_nazwa');
                    $autor_tytul = get_field('autor_tytul');
                    $autor_avatar = get_field('autor_avatar');
                ?>
                    <div class="swiper-slide">
                        <article class="opinia-card">
                            <blockquote class="opinia-card__quote">
                                <?php the_content(); ?>
                            </blockquote>
                            <footer class="opinia-card__author">
                                <?php if ($autor_avatar) : ?>
                                    <img src="<?php echo esc_url($autor_avatar['sizes']['thumbnail']); ?>"
                                         alt="<?php echo esc_attr($autor_nazwa); ?>"
                                         class="opinia-card__avatar">
                                <?php endif; ?>
                                <div class="opinia-card__author-info">
                                    <cite class="opinia-card__name"><?php echo esc_html($autor_nazwa); ?></cite>
                                    <?php if ($autor_tytul) : ?>
                                        <span class="opinia-card__role"><?php echo esc_html($autor_tytul); ?></span>
                                    <?php endif; ?>
                                </div>
                            </footer>
                        </article>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>

        <div class="opinie-navigation">
            <button class="opinie-prev" aria-label="Poprzednia opinia">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
            </button>
            <button class="opinie-next" aria-label="Następna opinia">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 18l6-6-6-6"/>
                </svg>
            </button>
        </div>
    </div>
</section>
