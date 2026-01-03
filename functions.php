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
            HELLO_ELEMENTOR_CHILD_VERSION
        );
    }
}
add_action('wp_enqueue_scripts', 'child_assets', 100);

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
        'posts_per_page' => absint($atts['count']),
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
    <div class="featured-paintings">
        <?php while ($featured_paintings->have_posts()) : $featured_paintings->the_post(); ?>
            <?php render_painting_card(); ?>
        <?php endwhile; ?>
    </div>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('featured_paintings', 'featured_paintings_shortcode');