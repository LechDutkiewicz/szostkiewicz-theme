<?php
/**
 * Template for single Obraz (painting) post type
 * 
 * @package Hello_Theme_Child
 */

get_header();

// Pobierz dane ACF
$galeria = get_field('galeria_obrazu');
$o_obrazie = get_field('o_obrazie');
$wymiary = $o_obrazie['wymiary_obrazu'] ?? '';
$cena = $o_obrazie['cena_obrazu'] ?? '';
$opis = $o_obrazie['opis_obrazu'] ?? '';

// Pobierz kolekcję (taxonomy)
$kolekcje = get_the_terms(get_the_ID(), 'kolekcja');
$kolekcja_name = $kolekcje ? $kolekcje[0]->name : '';
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
                    <span class="obraz-hero__kolekcja"><?php echo esc_html($kolekcja_name); ?></span>
                <?php endif; ?>
                
                <h1 class="obraz-hero__title"><?php the_title(); ?></h1>
                
                <?php if ($opis): ?>
                    <div class="obraz-hero__opis">
                        <?php echo wp_kses_post($opis); ?>
                    </div>
                <?php endif; ?>

                <dl class="obraz-hero__meta">
                    <?php if ($wymiary): ?>
                        <div class="obraz-hero__meta-item">
                            <dt>Rozmiar obrazu:</dt>
                            <dd><?php echo esc_html($wymiary); ?></dd>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($cena): ?>
                        <div class="obraz-hero__meta-item obraz-hero__meta-item--cena">
                            <dt>Cena:</dt>
                            <dd><?php echo esc_html($cena); ?> zł</dd>
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
    <?php
    $opinie = new WP_Query([
        'post_type' => 'opinia',
        'posts_per_page' => 10,
        'orderby' => 'menu_order',
        'order' => 'ASC',
    ]);
    
    if ($opinie->have_posts()):
    ?>
    <section class="opinie-section">
        <div class="opinie-section__background"></div>
        <div class="opinie-section__container">
            <h2 class="opinie-section__title">Sztuka, która przemawia do duszy</h2>
            
            <div class="swiper opinie-swiper">
                <div class="swiper-wrapper">
                    <?php while ($opinie->have_posts()): $opinie->the_post(); 
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
                                    <?php if ($autor_avatar): ?>
                                        <img src="<?php echo esc_url($autor_avatar['sizes']['thumbnail']); ?>" 
                                             alt="<?php echo esc_attr($autor_nazwa); ?>"
                                             class="opinia-card__avatar">
                                    <?php endif; ?>
                                    <div class="opinia-card__author-info">
                                        <cite class="opinia-card__name"><?php echo esc_html($autor_nazwa); ?></cite>
                                        <?php if ($autor_tytul): ?>
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
    <?php endif; ?>

    <!-- SEKCJA 3: Instagram (statyczna) -->
    <?php 
    $instagram_url = get_theme_mod('instagram_url', '');
    $instagram_images = get_theme_mod('instagram_images', []);
    
    // Alternatywnie: pobierz z opcji ACF
    if (function_exists('get_field')) {
        $instagram_options = get_field('instagram', 'option');
        if ($instagram_options) {
            $instagram_url = $instagram_options['instagram_url'] ?? $instagram_url;
            $instagram_images = $instagram_options['instagram_galeria'] ?? $instagram_images;
        }
    }
    
    if ($instagram_images && is_array($instagram_images) && count($instagram_images) > 0):
    ?>
    <section class="instagram-section">
        <div class="instagram-section__container">
            <h2 class="instagram-section__title">Artystyczne inspiracje i osobiste historie</h2>
            
            <div class="instagram-section__grid">
                <?php 
                $count = 0;
                foreach ($instagram_images as $image): 
                    if ($count >= 4) break;
                    $img_url = is_array($image) ? $image['url'] : $image;
                    $img_alt = is_array($image) ? ($image['alt'] ?? 'Instagram') : 'Instagram';
                ?>
                    <a href="<?php echo esc_url($instagram_url); ?>" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="instagram-section__item">
                        <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($img_alt); ?>">
                    </a>
                <?php 
                    $count++;
                endforeach; 
                ?>
            </div>
            
            <?php if ($instagram_url): ?>
                <a href="<?php echo esc_url($instagram_url); ?>" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="instagram-section__link">
                    Obserwuj mnie na IG →
                </a>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
