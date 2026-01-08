<?php
/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_CHILD_VERSION', '2.1.0' );

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles() {

    wp_enqueue_style(
        'hello-elementor-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        [
            'hello-elementor-theme-style',
        ],
        HELLO_ELEMENTOR_CHILD_VERSION
    );
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20 );


// Define theme paths
define('CHILD_THEME_PATH', get_stylesheet_directory() . '/');
define('CUSTOM_FIELDS_PATH', CHILD_THEME_PATH . 'lib/custom_fields/');

// Load template functions
require_once CHILD_THEME_PATH . 'inc/template-functions.php';

// Load custom post types
require_once CHILD_THEME_PATH . 'inc/custom-post-types.php';

// Load ACF field groups
require_once CHILD_THEME_PATH . 'inc/acf-fields.php';

/**
 * Get asset version based on file modification time for cache busting
 */
function get_asset_version($file_path) {
    $full_path = get_stylesheet_directory() . $file_path;
    return file_exists($full_path) ? filemtime($full_path) : HELLO_ELEMENTOR_CHILD_VERSION;
}

/**
 * Enqueue child theme assets
 */
function child_assets() {
    $css_files = [
        'variables',
        'typography',
        'elementor',
        'buttons',
        'effects',
        'navigation',
        'header',
        'footer',
        'sections',
        'archive',
        'featured-paintings',
        'single-obraz',
        'home',
    ];

    foreach ($css_files as $index => $file) {
        $handle = "child-theme-{$file}";
        $dependency = $index > 0 ? ["child-theme-{$css_files[$index - 1]}"] : [];

        wp_enqueue_style(
            $handle,
            get_stylesheet_directory_uri() . "/css/{$file}.css",
            $dependency,
            get_asset_version("/css/{$file}.css")
        );
    }

    // Enqueue Swiper (for sliders)
    wp_enqueue_style(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
        [],
        '11.0.0'
    );

    wp_enqueue_script(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        [],
        '11.0.0',
        true
    );

    // Enqueue Photoswipe CSS only (JS loaded via dynamic import in single-obraz.js)
    if (is_singular('obraz')) {
        wp_enqueue_style(
            'photoswipe',
            'https://cdn.jsdelivr.net/npm/photoswipe@5.4.3/dist/photoswipe.css',
            [],
            '5.4.3'
        );
    }

    // Enqueue single-obraz.js (for single painting pages)
    if (is_singular('obraz')) {
        wp_enqueue_script(
            'child-theme-single-obraz',
            get_stylesheet_directory_uri() . '/js/single-obraz.js',
            ['swiper'],
            get_asset_version('/js/single-obraz.js'),
            true
        );
    }

    // Enqueue featured-paintings.js (for homepage/shortcode)
    wp_enqueue_script(
        'child-theme-featured-paintings',
        get_stylesheet_directory_uri() . '/js/featured-paintings.js',
        ['swiper'],
        get_asset_version('/js/featured-paintings.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'child_assets', 100);

/**
 * Deregister jQuery on frontend (keep for admin and Elementor editor)
 * Can be overridden via ACF option in "Kontakt" settings
 */
function deregister_jquery_frontend() {
    // Don't deregister in admin
    if (is_admin()) {
        return;
    }

    // Don't deregister in Elementor preview/editor
    if (isset($_GET['elementor-preview']) || isset($_GET['elementor_library'])) {
        return;
    }

    // Check if Elementor is in preview mode
    if (class_exists('\Elementor\Plugin')) {
        if (\Elementor\Plugin::$instance->preview->is_preview_mode()) {
            return;
        }
    }

    // Check ACF option - if enabled, keep jQuery
    $enable_jquery = get_field('enable_jquery_frontend', 'option');
    if ($enable_jquery) {
        return;
    }

    // Deregister jQuery on frontend for better performance (~30KB saved)
    wp_deregister_script('jquery');
    wp_deregister_script('jquery-core');
    wp_deregister_script('jquery-migrate');
}
add_action('wp_enqueue_scripts', 'deregister_jquery_frontend', 100);

/**
 * Shortcode for displaying featured paintings
 *
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
function featured_paintings_shortcode($atts) {
    $atts = shortcode_atts([
        'count' => 3
    ], $atts, 'featured_paintings');

    $args = [
        'post_type' => 'obraz',
        'posts_per_page' => intval($atts['count']),
        'meta_query' => [
            [
                'key' => 'wyroznione_na_glownej',
                'value' => '1',
                'compare' => '='
            ]
        ]
    ];

    $featured_paintings = new WP_Query($args);

    if (!$featured_paintings->have_posts()) {
        return '';
    }

    ob_start();
    ?>
    <div class="featured-paintings-wrapper">
        <!-- Swiper Slider (Mobile & Desktop) -->
        <div class="swiper featured-paintings-swiper">
            <div class="swiper-wrapper">
                <?php while ($featured_paintings->have_posts()) : $featured_paintings->the_post(); ?>
                    <?php render_painting_card(null, ['slider_mode' => true]); ?>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- Navigation Arrows (Desktop only) -->
        <div class="featured-paintings-nav">
            <button class="featured-paintings-prev" aria-label="Poprzedni obraz">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
            </button>
            <button class="featured-paintings-next" aria-label="Następny obraz">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 18l6-6-6-6"/>
                </svg>
            </button>
        </div>
    </div>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('featured_paintings', 'featured_paintings_shortcode');