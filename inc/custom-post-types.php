<?php
/**
 * Custom Post Types Registration
 *
 * @package Hello_Theme_Child
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Obraz (Painting) Custom Post Type
 */
function register_obraz_post_type() {
    $labels = [
        'name'                  => 'Obrazy',
        'singular_name'         => 'Obraz',
        'menu_name'             => 'Obrazy',
        'name_admin_bar'        => 'Obraz',
        'add_new'               => 'Dodaj nowy',
        'add_new_item'          => 'Dodaj nowy obraz',
        'new_item'              => 'Nowy obraz',
        'edit_item'             => 'Edytuj obraz',
        'view_item'             => 'Zobacz obraz',
        'all_items'             => 'Wszystkie obrazy',
        'search_items'          => 'Szukaj obrazów',
        'parent_item_colon'     => 'Nadrzędny obraz:',
        'not_found'             => 'Nie znaleziono obrazów',
        'not_found_in_trash'    => 'Nie znaleziono obrazów w koszu',
        'featured_image'        => 'Zdjęcie wyróżniające',
        'set_featured_image'    => 'Ustaw zdjęcie wyróżniające',
        'remove_featured_image' => 'Usuń zdjęcie wyróżniające',
        'use_featured_image'    => 'Użyj jako zdjęcie wyróżniające',
        'archives'              => 'Archiwum obrazów',
        'insert_into_item'      => 'Wstaw do obrazu',
        'uploaded_to_this_item' => 'Przesłane do tego obrazu',
        'filter_items_list'     => 'Filtruj listę obrazów',
        'items_list_navigation' => 'Nawigacja listy obrazów',
        'items_list'            => 'Lista obrazów',
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => ['slug' => 'obraz'],
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-art',
        'show_in_rest'       => true,
        'supports'           => [
            'title',
            'editor',
            'thumbnail',
            'custom-fields',
            'page-attributes', // Enables Order field for manual sorting
        ],
    ];

    register_post_type('obraz', $args);
}
add_action('init', 'register_obraz_post_type');

/**
 * Register Kolekcja (Collection) Taxonomy
 */
function register_kolekcja_taxonomy() {
    $labels = [
        'name'                       => 'Kolekcje',
        'singular_name'              => 'Kolekcja',
        'menu_name'                  => 'Kolekcje',
        'all_items'                  => 'Wszystkie kolekcje',
        'parent_item'                => 'Nadrzędna kolekcja',
        'parent_item_colon'          => 'Nadrzędna kolekcja:',
        'new_item_name'              => 'Nazwa nowej kolekcji',
        'add_new_item'               => 'Dodaj nową kolekcję',
        'edit_item'                  => 'Edytuj kolekcję',
        'update_item'                => 'Aktualizuj kolekcję',
        'view_item'                  => 'Zobacz kolekcję',
        'separate_items_with_commas' => 'Oddziel kolekcje przecinkami',
        'add_or_remove_items'        => 'Dodaj lub usuń kolekcje',
        'choose_from_most_used'      => 'Wybierz z najczęściej używanych',
        'popular_items'              => 'Popularne kolekcje',
        'search_items'               => 'Szukaj kolekcji',
        'not_found'                  => 'Nie znaleziono',
        'no_terms'                   => 'Brak kolekcji',
        'items_list'                 => 'Lista kolekcji',
        'items_list_navigation'      => 'Nawigacja listy kolekcji',
    ];

    $args = [
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'kolekcja'],
    ];

    register_taxonomy('kolekcja', ['obraz'], $args);
}
add_action('init', 'register_kolekcja_taxonomy');

/**
 * Modify archive query to show 12 posts per page
 */
function modify_obraz_archive_query($query) {
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('obraz')) {
        $query->set('posts_per_page', 12);
        $query->set('orderby', 'menu_order');
        $query->set('order', 'ASC');
    }
}
add_action('pre_get_posts', 'modify_obraz_archive_query');

/**
 * Add thumbnail column to Obraz admin list
 */
function add_obraz_thumbnail_column($columns) {
    $new_columns = [];

    foreach ($columns as $key => $value) {
        if ($key === 'title') {
            $new_columns['thumbnail'] = 'Miniatura';
        }
        $new_columns[$key] = $value;
    }

    return $new_columns;
}
add_filter('manage_obraz_posts_columns', 'add_obraz_thumbnail_column');

/**
 * Display thumbnail in admin column
 */
function display_obraz_thumbnail_column($column, $post_id) {
    if ($column === 'thumbnail') {
        $thumbnail = get_the_post_thumbnail($post_id, [60, 60]);
        echo $thumbnail ?: '<span style="color: #999;">—</span>';
    }
}
add_action('manage_obraz_posts_custom_column', 'display_obraz_thumbnail_column', 10, 2);

/**
 * Make thumbnail column sortable
 */
function make_obraz_thumbnail_column_sortable($columns) {
    $columns['thumbnail'] = 'thumbnail';
    return $columns;
}
add_filter('manage_edit-obraz_sortable_columns', 'make_obraz_thumbnail_column_sortable');

/**
 * Add drag handle column for manual ordering
 */
function add_obraz_order_column($columns) {
    $new_columns = [];

    // Add order column at the beginning
    $new_columns['order'] = '';

    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
    }

    return $new_columns;
}
add_filter('manage_obraz_posts_columns', 'add_obraz_order_column', 5);

/**
 * Display order number in column
 */
function display_obraz_order_column($column, $post_id) {
    if ($column === 'order') {
        $post = get_post($post_id);
        echo '<span class="dashicons dashicons-menu" style="color: #999;"></span>';
        echo '<span style="margin-left: 5px; color: #666;">' . $post->menu_order . '</span>';
    }
}
add_action('manage_obraz_posts_custom_column', 'display_obraz_order_column', 5, 2);

/**
 * Make order column narrow
 */
function obraz_admin_column_css() {
    echo '<style>
        .column-order { width: 60px; text-align: center; }
        .column-thumbnail { width: 80px; }
        .column-thumbnail img { border-radius: 4px; }
    </style>';
}
add_action('admin_head', 'obraz_admin_column_css');

/**
 * Add inline CSS and JS for better admin experience with drag & drop
 */
function obraz_admin_scripts() {
    global $typenow;

    if ($typenow === 'obraz') {
        // Enqueue WordPress sortable
        wp_enqueue_script('jquery-ui-sortable');
        ?>
        <style>
            .wp-list-table .column-order {
                cursor: move;
            }
            .wp-list-table tr:hover .column-order .dashicons {
                color: #2271b1;
            }
            .wp-list-table tbody tr.ui-sortable-helper {
                background: #f0f0f1;
                opacity: 0.8;
            }
            .wp-list-table tbody tr.ui-sortable-placeholder {
                background: #e5f5fa;
                border: 2px dashed #2271b1;
            }
        </style>
        <script>
        jQuery(document).ready(function($) {
            var $table = $('.wp-list-table tbody');

            $table.sortable({
                items: 'tr',
                cursor: 'move',
                axis: 'y',
                handle: '.column-order',
                placeholder: 'ui-sortable-placeholder',
                helper: function(e, tr) {
                    var $originals = tr.children();
                    var $helper = tr.clone();
                    $helper.children().each(function(index) {
                        $(this).width($originals.eq(index).width());
                    });
                    return $helper;
                },
                update: function(event, ui) {
                    var order = [];
                    $table.find('tr').each(function(index) {
                        var postId = $(this).attr('id');
                        if (postId) {
                            postId = postId.replace('post-', '');
                            order.push({
                                id: postId,
                                position: index
                            });
                        }
                    });

                    // Save order via AJAX
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'update_obraz_order',
                            order: order,
                            nonce: '<?php echo wp_create_nonce('obraz_order_nonce'); ?>'
                        },
                        success: function(response) {
                            if (response.success) {
                                // Update order numbers in UI
                                $table.find('tr').each(function(index) {
                                    $(this).find('.column-order span:last').text(index);
                                });
                            }
                        }
                    });
                }
            });
        });
        </script>
        <?php
    }
}
add_action('admin_head', 'obraz_admin_scripts');

/**
 * AJAX handler for updating post order
 */
function update_obraz_order_ajax() {
    check_ajax_referer('obraz_order_nonce', 'nonce');

    if (!current_user_can('edit_posts')) {
        wp_send_json_error('Insufficient permissions');
    }

    $order = isset($_POST['order']) ? $_POST['order'] : [];

    foreach ($order as $item) {
        $post_id = absint($item['id']);
        $position = absint($item['position']);

        wp_update_post([
            'ID' => $post_id,
            'menu_order' => $position
        ]);
    }

    wp_send_json_success('Order updated');
}
add_action('wp_ajax_update_obraz_order', 'update_obraz_order_ajax');
