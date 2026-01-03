<?php
/**
 * Template part for displaying Instagram section
 *
 * @package Hello_Theme_Child
 */

if (!defined('ABSPATH')) {
    exit;
}

$instagram = get_instagram_settings();

if (empty($instagram['images']) || !is_array($instagram['images'])) {
    return;
}
?>

<section class="instagram-section">
    <div class="instagram-section__container">
        <h2 class="instagram-section__title">Artystyczne inspiracje i osobiste historie</h2>

        <div class="instagram-section__grid">
            <?php
            $count = 0;
            foreach ($instagram['images'] as $image) :
                if ($count >= 4) break;
                $img_url = is_array($image) ? $image['url'] : $image;
                $img_alt = is_array($image) ? ($image['alt'] ?? 'Instagram') : 'Instagram';
            ?>
                <a href="<?php echo $instagram['url']; ?>"
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

        <?php if ($instagram['url']) : ?>
            <a href="<?php echo $instagram['url']; ?>"
               target="_blank"
               rel="noopener noreferrer"
               class="instagram-section__link">
                Obserwuj mnie na IG →
            </a>
        <?php endif; ?>
    </div>
</section>
