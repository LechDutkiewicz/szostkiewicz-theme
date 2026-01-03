<?php
/**
 * Template for single Obraz (painting) post type
 * 
 * @package Hello_Theme_Child
 */

get_header();

// Pobierz dane ACF
$galeria = get_field('galeria_obrazu');
$metadata = get_painting_metadata();

// Pobierz kolekcję (taxonomy)
$kolekcje = get_the_terms(get_the_ID(), 'kolekcja');
$kolekcja_name = $kolekcje && !is_wp_error($kolekcje) ? esc_html($kolekcje[0]->name) : '';
?>

<main id="content" class="site-main obraz-single">

    <!-- SEKCJA 1: Hero z obrazem -->
    <section class="obraz-hero">
        <div class="obraz-hero__container">
            
            <!-- Lewa strona: Galeria -->
            <div class="obraz-hero__gallery">
                <?php if ($galeria && count($galeria) > 0): ?>
                    
                    <?php if (count($galeria) === 1): ?>
                        <!-- Pojedynczy obraz -->
                        <div class="obraz-hero__single-image">
                            <img src="<?php echo esc_url($galeria[0]['url']); ?>" 
                                 alt="<?php echo esc_attr($galeria[0]['alt'] ?: get_the_title()); ?>"
                                 loading="eager">
                        </div>
                    
                    <?php else: ?>
                        <!-- Swiper galeria -->
                        <div class="swiper obraz-swiper">
                            <div class="swiper-wrapper">
                                <?php foreach ($galeria as $image): ?>
                                    <div class="swiper-slide">
                                        <img src="<?php echo esc_url($image['url']); ?>" 
                                             alt="<?php echo esc_attr($image['alt'] ?: get_the_title()); ?>"
                                             loading="lazy">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                        </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <!-- Fallback jeśli brak obrazów -->
                    <div class="obraz-hero__no-image">
                        <span>Brak zdjęcia</span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Prawa strona: Informacje -->
            <div class="obraz-hero__info">
                <?php if ($kolekcja_name): ?>
                    <span class="obraz-hero__kolekcja"><?php echo $kolekcja_name; ?></span>
                <?php endif; ?>

                <h1 class="obraz-hero__title"><?php the_title(); ?></h1>

                <?php if ($metadata['description']): ?>
                    <div class="obraz-hero__opis">
                        <?php echo $metadata['description']; ?>
                    </div>
                <?php endif; ?>

                <dl class="obraz-hero__meta">
                    <?php if ($metadata['dimensions']): ?>
                        <div class="obraz-hero__meta-item">
                            <dt>Rozmiar obrazu:</dt>
                            <dd><?php echo $metadata['dimensions']; ?></dd>
                        </div>
                    <?php endif; ?>

                    <?php if ($metadata['price']): ?>
                        <div class="obraz-hero__meta-item obraz-hero__meta-item--cena">
                            <dt>Cena:</dt>
                            <dd><?php echo $metadata['price']; ?> zł</dd>
                        </div>
                    <?php endif; ?>
                </dl>

                <a href="<?php echo esc_url(home_url('/kontakt')); ?>" class="obraz-hero__cta">
                    Skontaktuj się w celu zakupu
                </a>
            </div>

        </div>
    </section>

    <!-- SEKCJA 2: Opinie -->
    <?php get_template_part('template-parts/section', 'opinie'); ?>

    <!-- SEKCJA 3: Instagram -->
    <?php get_template_part('template-parts/section', 'instagram'); ?>

</main>

<?php get_footer(); ?>
