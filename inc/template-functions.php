<?php
/**
 * Template helper functions
 *
 * @package Hello_Theme_Child
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get painting metadata from ACF
 *
 * @param int|null $post_id Post ID (null for current post)
 * @return array Sanitized painting metadata
 */
function get_painting_metadata($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    $o_obrazie = get_field('o_obrazie', $post_id);

    return [
        'dimensions' => !empty($o_obrazie['wymiary_obrazu']) ? sanitize_text_field($o_obrazie['wymiary_obrazu']) : '',
        'price' => !empty($o_obrazie['cena_obrazu']) ? sanitize_text_field($o_obrazie['cena_obrazu']) : '',
        'description' => !empty($o_obrazie['opis_obrazu']) ? wp_kses_post($o_obrazie['opis_obrazu']) : '',
    ];
}

/**
 * Render painting card
 *
 * @param int|null $post_id Post ID (null for current post)
 * @param array $args Additional arguments
 */
function render_painting_card($post_id = null, $args = []) {
    $post_id = $post_id ?: get_the_ID();
    $metadata = get_painting_metadata($post_id);

    $defaults = [
        'show_image' => true,
        'show_title' => true,
        'show_dimensions' => true,
        'show_price' => true,
        'show_description' => true,
        'link' => true,
        'image_size' => 'medium',
        'image_class' => 'shadow-black',
        'slider_mode' => false,
    ];

    $args = wp_parse_args($args, $defaults);

    $wrapper_class = $args['slider_mode'] ? 'swiper-slide' : '';
    $tag = $args['link'] ? 'a' : 'div';
    $href = $args['link'] ? sprintf(' href="%s"', esc_url(get_permalink($post_id))) : '';

    if ($args['slider_mode']) {
        echo '<div class="swiper-slide">';
    }

    printf('<%s class="painting-item"%s>', $tag, $href);

    if ($args['show_image'] && has_post_thumbnail($post_id)) {
        echo get_the_post_thumbnail($post_id, $args['image_size'], ['class' => $args['image_class']]);
    }

    if ($args['show_title']) {
        printf('<h3 class="h6">%s</h3>', get_the_title($post_id));
    }

    if ($args['show_dimensions'] && $metadata['dimensions']) {
        printf('<span class="dimensions">%s</span>', esc_html($metadata['dimensions']));
    }

    if ($args['show_price'] && $metadata['price']) {
        printf('<span class="price">%s</span>', esc_html($metadata['price']));
    }

    if ($args['show_description'] && $metadata['description']) {
        printf('<div class="description">%s</div>', $metadata['description']);
    }

    printf('</%s>', $tag);

    if ($args['slider_mode']) {
        echo '</div>';
    }
}

/**
 * Get Instagram settings from ACF options
 *
 * @return array Instagram URL and images
 */
function get_instagram_settings() {
    $instagram_url = '';
    $instagram_images = [];

    if (function_exists('get_field')) {
        $instagram_options = get_field('instagram', 'option');
        if ($instagram_options) {
            $instagram_url = $instagram_options['instagram_url'] ?? '';
            $instagram_images = $instagram_options['instagram_galeria'] ?? [];
        }
    }

    // Fallback to theme_mod
    if (empty($instagram_url)) {
        $instagram_url = get_theme_mod('instagram_url', '');
    }
    if (empty($instagram_images)) {
        $instagram_images = get_theme_mod('instagram_images', []);
    }

    return [
        'url' => esc_url($instagram_url),
        'images' => $instagram_images,
    ];
}

/**
 * Check if using Elementor header/footer
 *
 * @return bool
 */
function is_using_elementor_header_footer() {
    $elementor_header = get_option('elementor_header_template_id');
    $elementor_footer = get_option('elementor_footer_template_id');

    return !empty($elementor_header) || !empty($elementor_footer);
}
