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
$contact_links = get_contact_links();

// Pobierz kolekcję (taxonomy)
$kolekcje = get_the_terms(get_the_ID(), 'kolekcja');
$kolekcja_name = $kolekcje && !is_wp_error($kolekcje) ? esc_html($kolekcje[0]->name) : '';
?>

<main id="content" class="site-main obraz-single">

    <!-- Breadcrumbs -->
    <nav class="breadcrumbs" aria-label="Breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>">Strona główna</a>
        <span class="breadcrumbs__separator">/</span>
        <a href="<?php echo esc_url(get_post_type_archive_link('obraz')); ?>">Galeria</a>
        <span class="breadcrumbs__separator">/</span>
        <span class="breadcrumbs__current">obraz</span>
    </nav>

    <!-- SEKCJA 1: Hero z obrazem -->
    <section class="obraz-hero">
        <div class="obraz-hero__container">
            
            <!-- Lewa strona: Galeria -->
            <div class="obraz-hero__gallery">
                <?php if ($galeria && count($galeria) > 0): ?>

                    <?php if (count($galeria) === 1): ?>
                        <!-- Pojedynczy obraz z galerii ACF -->
                        <div class="obraz-hero__single-image pswp-gallery">
                            <?php
                            $image_id = $galeria[0]['ID'];
                            $full_url = $galeria[0]['url'];
                            $mobile_url = wp_get_attachment_image_url($image_id, 'medium_large') ?: $full_url;
                            $is_mobile = wp_is_mobile();
                            $target_url = $is_mobile ? $mobile_url : $full_url;

                            // Get image dimensions for target URL
                            $image_metadata = wp_get_attachment_metadata($image_id);
                            if ($is_mobile) {
                                $size_data = wp_get_attachment_image_src($image_id, 'medium_large');
                                $width = $size_data[1];
                                $height = $size_data[2];
                            } else {
                                $width = $image_metadata['width'] ?? 1200;
                                $height = $image_metadata['height'] ?? 900;
                            }
                            ?>
                            <a href="<?php echo esc_url($target_url); ?>"
                               data-pswp-src="<?php echo esc_url($target_url); ?>"
                               data-pswp-width="<?php echo $width; ?>"
                               data-pswp-height="<?php echo $height; ?>"
                               class="pswp-gallery__item">
                                <img src="<?php echo esc_url($galeria[0]['url']); ?>"
                                     alt="<?php echo esc_attr($galeria[0]['alt'] ?: get_the_title()); ?>"
                                     loading="eager"
                                     style="cursor: pointer;">
                            </a>
                        </div>

                    <?php else: ?>
                        <!-- Swiper galeria (wiele obrazów) -->
                        <div class="swiper obraz-swiper pswp-gallery">
                            <div class="swiper-wrapper">
                                <?php foreach ($galeria as $image): ?>
                                    <?php
                                    $image_id = $image['ID'];
                                    $full_url = $image['url'];
                                    $mobile_url = wp_get_attachment_image_url($image_id, 'medium_large') ?: $full_url;
                                    $is_mobile = wp_is_mobile();
                                    $target_url = $is_mobile ? $mobile_url : $full_url;

                                    // Get image dimensions
                                    $image_metadata = wp_get_attachment_metadata($image_id);
                                    if ($is_mobile) {
                                        $size_data = wp_get_attachment_image_src($image_id, 'medium_large');
                                        $width = $size_data[1];
                                        $height = $size_data[2];
                                    } else {
                                        $width = $image_metadata['width'] ?? 1200;
                                        $height = $image_metadata['height'] ?? 900;
                                    }
                                    ?>
                                    <div class="swiper-slide">
                                        <a href="<?php echo esc_url($target_url); ?>"
                                           data-pswp-src="<?php echo esc_url($target_url); ?>"
                                           data-pswp-width="<?php echo $width; ?>"
                                           data-pswp-height="<?php echo $height; ?>"
                                           class="pswp-gallery__item">
                                            <img src="<?php echo esc_url($image['url']); ?>"
                                                 alt="<?php echo esc_attr($image['alt'] ?: get_the_title()); ?>"
                                                 loading="lazy"
                                                 style="cursor: pointer;">
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                        </div>
                    <?php endif; ?>

                <?php elseif (has_post_thumbnail()): ?>
                    <!-- Fallback do Featured Image -->
                    <div class="obraz-hero__single-image pswp-gallery">
                        <?php
                        $thumbnail_id = get_post_thumbnail_id();
                        $full_url = wp_get_attachment_image_url($thumbnail_id, 'full');
                        $mobile_url = wp_get_attachment_image_url($thumbnail_id, 'medium_large') ?: $full_url;
                        $large_url = wp_get_attachment_image_url($thumbnail_id, 'large');
                        $is_mobile = wp_is_mobile();
                        $target_url = $is_mobile ? $mobile_url : $full_url;

                        // Get image dimensions
                        $image_metadata = wp_get_attachment_metadata($thumbnail_id);
                        if ($is_mobile) {
                            $size_data = wp_get_attachment_image_src($thumbnail_id, 'medium_large');
                            $width = $size_data[1];
                            $height = $size_data[2];
                        } else {
                            $width = $image_metadata['width'] ?? 1200;
                            $height = $image_metadata['height'] ?? 900;
                        }
                        ?>
                        <a href="<?php echo esc_url($target_url); ?>"
                           data-pswp-src="<?php echo esc_url($target_url); ?>"
                           data-pswp-width="<?php echo $width; ?>"
                           data-pswp-height="<?php echo $height; ?>"
                           class="pswp-gallery__item">
                            <img src="<?php echo esc_url($large_url); ?>"
                                 alt="<?php echo esc_attr(get_the_title()); ?>"
                                 loading="eager"
                                 style="cursor: pointer;">
                        </a>
                    </div>

                <?php else: ?>
                    <!-- Brak zdjęć (ani galeria, ani featured image) -->
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

                <h1 class="h5"><?php the_title(); ?></h1>

                <?php if ($metadata['description']): ?>
                    <div class="obraz-hero__opis">
                        <?php echo $metadata['description']; ?>
                    </div>
                <?php endif; ?>

                <dl class="obraz-hero__meta">
                    <?php if ($metadata['dimensions']): ?>
                        <div class="obraz-hero__meta-item">
                            <dt>Rozmiar:</dt>
                            <dd><?php echo $metadata['dimensions']; ?></dd>
                        </div>
                    <?php endif; ?>

                    <?php if ($metadata['year']): ?>
                        <div class="obraz-hero__meta-item">
                            <dt>Rok powstania:</dt>
                            <dd><?php echo $metadata['year']; ?></dd>
                        </div>
                    <?php endif; ?>

                    <?php if ($metadata['technique']): ?>
                        <div class="obraz-hero__meta-item">
                            <dt>Technika:</dt>
                            <dd><?php echo $metadata['technique']; ?></dd>
                        </div>
                    <?php endif; ?>

                    <?php if ($metadata['signature']): ?>
                        <div class="obraz-hero__meta-item">
                            <dt>Sygnatura:</dt>
                            <dd><?php echo $metadata['signature']; ?></dd>
                        </div>
                    <?php endif; ?>

                    <div class="obraz-hero__meta-item obraz-hero__meta-item--cena">
                        <dt>Cena:</dt>
                        <dd>
                            <?php if ($metadata['price']): ?>
                                <?php echo $metadata['price']; ?> zł
                            <?php else: ?>
                                <span class="sold">sprzedany</span>
                            <?php endif; ?>
                        </dd>
                    </div>
                </dl>

                <?php if (($contact_links['messenger'] || $contact_links['whatsapp']) && $metadata['price']): ?>
                    <div class="obraz-hero__contact">
                        <h2 class="h6">Napisz do mnie</h2>
                        <div class="obraz-hero__contact-links">
                            <?php if ($contact_links['messenger']): ?>
                                <a href="<?php echo $contact_links['messenger']; ?>" class="contact-link contact-link--messenger" target="_blank" rel="noopener">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2C6.477 2 2 6.145 2 11.243c0 2.913 1.446 5.511 3.707 7.206V22l3.395-1.864c.906.252 1.87.388 2.898.388 5.523 0 10-4.145 10-9.243C22 6.145 17.523 2 12 2zm.945 12.434l-2.561-2.735-5.001 2.735 5.501-5.84 2.622 2.735 4.94-2.735-5.501 5.84z"/>
                                    </svg>
                                    <span>Messenger</span>
                                </a>
                            <?php endif; ?>

                            <?php if ($contact_links['whatsapp']): ?>
                                <a href="<?php echo $contact_links['whatsapp']; ?>" class="contact-link contact-link--whatsapp" target="_blank" rel="noopener">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                    <span>WhatsApp</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </section>

    <!-- SEKCJA 2: Opinie -->
    <?php get_template_part('template-parts/section', 'opinie'); ?>

    <!-- SEKCJA 3: Instagram -->
    <?php get_template_part('template-parts/section', 'instagram'); ?>

</main>

<?php get_footer(); ?>
