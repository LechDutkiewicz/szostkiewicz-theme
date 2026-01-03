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


// define theme paths for template and extensions
define('CHILD_THEME_PATH',get_stylesheet_directory().'/');
define('CUSTOM_FIELDS_PATH',CHILD_THEME_PATH.'lib/custom_fields/');

/* add css */
function child_assets() {
    $css_files = [
        'variables',
        'typography',
        'elementor',
        'buttons',
        'effects',
		'header',
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

/* shortcode dla wyświetlania listy wybranych obrazów */
function featured_paintings_shortcode($atts) {
    $atts = shortcode_atts(array(
        'count' => 3
    ), $atts);
    
    // Query dla postów z polem featured = true
    $args = array(
        'post_type' => 'obraz',
        'posts_per_page' => $atts['count'],
        'meta_query' => array(
            array(
                'key' => 'wyroznione_na_glownej',
                'value' => '1',
                'compare' => '='
            )
        )
    );
    
    $featured_paintings = new WP_Query($args);
    
    ob_start();
    
    if ($featured_paintings->have_posts()) : ?>
        <div class="featured-paintings">
            <?php while ($featured_paintings->have_posts()) : $featured_paintings->the_post(); 
                // opisy
                $dimensions = get_field('o_obrazie')['wymiary_obrazu'];
                $price = get_field('o_obrazie')['cena_obrazu'];
                $description = get_field('o_obrazie')['opis_obrazu'];
                //$first_image = $gallery ? $gallery[0] : null;
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
    <?php endif;
    
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('featured_paintings', 'featured_paintings_shortcode');