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
 * Disable WordPress emoji for performance (~5KB saved)
 */
function disable_wordpress_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'disable_wordpress_emojis');

/**
 * Remove emoji from TinyMCE
 */
function disable_emojis_tinymce($plugins) {
    if (is_array($plugins)) {
        return array_diff($plugins, array('wpemoji'));
    }
    return array();
}
add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');

/**
 * Remove emoji DNS prefetch
 */
function disable_emojis_remove_dns_prefetch($urls, $relation_type) {
    if ('dns-prefetch' == $relation_type) {
        $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');
        $urls = array_diff($urls, array($emoji_svg_url));
    }
    return $urls;
}
add_filter('wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2);

/**
 * Preload critical fonts for better LCP
 */
function preload_critical_fonts() {
    // Get Elementor kit ID (usually kit-80 or similar)
    $kit_id = get_option('elementor_active_kit');

    if (!$kit_id) {
        return;
    }

    // Preload Source Sans Pro (body font)
    echo '<link rel="preload" as="font" type="font/woff2" crossorigin href="https://fonts.gstatic.com/s/sourcesanspro/v22/6xKydSBYKcSV-LCoeQqfX1RYOo3ik4zwmhduz8A.woff2">' . "\n";

    // Preload Playfair Display (heading font)
    echo '<link rel="preload" as="font" type="font/woff2" crossorigin href="https://fonts.gstatic.com/s/playfairdisplay/v37/nuFkD-vYSZviVYUb_rj3ij__anPXDTnCjmHKM4nYO7KN_qiTbtY.woff2">' . "\n";
}
add_action('wp_head', 'preload_critical_fonts', 1);

/**
 * Add cache control headers for static assets
 */
function add_cache_headers() {
    if (!is_admin()) {
        // 1 year cache for images, fonts, CSS, JS
        header('Cache-Control: public, max-age=31536000, immutable');
    }
}
// Note: This sets headers but .htaccess is more reliable for static assets
// Add this to .htaccess for better control:
/*
<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresByType image/jpg "access plus 1 year"
  ExpiresByType image/jpeg "access plus 1 year"
  ExpiresByType image/gif "access plus 1 year"
  ExpiresByType image/png "access plus 1 year"
  ExpiresByType image/webp "access plus 1 year"
  ExpiresByType text/css "access plus 1 month"
  ExpiresByType application/javascript "access plus 1 month"
  ExpiresByType application/x-javascript "access plus 1 month"
  ExpiresByType font/woff "access plus 1 year"
  ExpiresByType font/woff2 "access plus 1 year"
</IfModule>
*/

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