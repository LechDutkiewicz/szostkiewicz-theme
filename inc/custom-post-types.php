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
 * Add custom columns to Obraz admin list
 */
function add_obraz_custom_columns($columns) {
    $new_columns = [];

    foreach ($columns as $key => $value) {
        if ($key === 'title') {
            $new_columns['thumbnail'] = 'Miniatura';
        }
        $new_columns[$key] = $value;

        // Add custom columns after title
        if ($key === 'title') {
            $new_columns['year'] = 'Rok';
            $new_columns['price'] = 'Cena';
        }
    }

    return $new_columns;
}
add_filter('manage_obraz_posts_columns', 'add_obraz_custom_columns');

/**
 * Display custom columns in admin
 */
function display_obraz_custom_columns($column, $post_id) {
    if ($column === 'thumbnail') {
        $thumbnail = get_the_post_thumbnail($post_id, [60, 60]);
        echo $thumbnail ?: '<span style="color: #999;">—</span>';
    }

    if ($column === 'year') {
        $metadata = get_painting_metadata($post_id);
        echo $metadata['year'] ?: '<span style="color: #999;">—</span>';
    }

    if ($column === 'price') {
        $metadata = get_painting_metadata($post_id);
        $price = $metadata['price'];

        if ($price) {
            echo '<span class="obraz-price-display" data-post-id="' . $post_id . '" data-price="' . esc_attr($price) . '">';
            echo esc_html($price) . ' zł';
            echo '</span>';
            echo ' <button type="button" class="button button-small obraz-edit-price" data-post-id="' . $post_id . '" style="margin-left: 5px;">Edytuj</button>';
        } else {
            echo '<span class="obraz-price-display" data-post-id="' . $post_id . '" data-price="" style="color: #999;">sprzedany</span>';
            echo ' <button type="button" class="button button-small obraz-edit-price" data-post-id="' . $post_id . '" style="margin-left: 5px;">Ustaw cenę</button>';
        }
    }
}
add_action('manage_obraz_posts_custom_column', 'display_obraz_custom_columns', 10, 2);

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
 * Column width and styling
 */
function obraz_admin_column_css() {
    echo '<style>
        .column-order { width: 60px; text-align: center; }
        .column-thumbnail { width: 80px; }
        .column-thumbnail img { border-radius: 4px; }
        .column-year { width: 80px; }
        .column-price { width: 150px; }
        .obraz-price-edit {
            display: none;
            margin-top: 5px;
        }
        .obraz-price-edit input {
            width: 80px;
            margin-right: 5px;
        }
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

            // Drag & Drop sorting
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

            // Inline price edit
            $(document).on('click', '.obraz-edit-price', function(e) {
                e.preventDefault();
                var $button = $(this);
                var $cell = $button.closest('td');
                var $display = $cell.find('.obraz-price-display');
                var postId = $button.data('post-id');
                var currentPrice = $display.data('price') || '';

                // Check if edit form already exists
                if ($cell.find('.obraz-price-edit').length > 0) {
                    return;
                }

                // Hide display and button
                $display.hide();
                $button.hide();

                // Create edit form
                var $editForm = $('<div class="obraz-price-edit"></div>');
                $editForm.append('<input type="number" class="obraz-price-input" value="' + currentPrice + '" placeholder="Cena" step="1" min="0">');
                $editForm.append('<button type="button" class="button button-small obraz-save-price">Zapisz</button>');
                $editForm.append('<button type="button" class="button button-small obraz-cancel-price">Anuluj</button>');

                $cell.append($editForm);
                $editForm.show();
                $editForm.find('.obraz-price-input').focus();

                // Save on Enter
                $editForm.find('.obraz-price-input').on('keypress', function(e) {
                    if (e.which === 13) {
                        $editForm.find('.obraz-save-price').click();
                    }
                });
            });

            // Save price
            $(document).on('click', '.obraz-save-price', function() {
                var $button = $(this);
                var $cell = $button.closest('td');
                var $editForm = $cell.find('.obraz-price-edit');
                var $input = $editForm.find('.obraz-price-input');
                var $display = $cell.find('.obraz-price-display');
                var $editButton = $cell.find('.obraz-edit-price');
                var postId = $editButton.data('post-id');
                var newPrice = $input.val().trim();

                // Disable button during save
                $button.prop('disabled', true).text('Zapisywanie...');

                // Save via AJAX
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'update_obraz_price',
                        post_id: postId,
                        price: newPrice,
                        nonce: '<?php echo wp_create_nonce('obraz_price_nonce'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update display
                            $display.data('price', newPrice);
                            if (newPrice) {
                                $display.html(newPrice + ' zł').css('color', '');
                                $editButton.text('Edytuj');
                            } else {
                                $display.html('sprzedany').css('color', '#999');
                                $editButton.text('Ustaw cenę');
                            }

                            // Remove edit form
                            $editForm.remove();

                            // Show display and button
                            $display.show();
                            $editButton.show();
                        } else {
                            alert('Błąd: ' + (response.data || 'Nie udało się zapisać ceny'));
                            $button.prop('disabled', false).text('Zapisz');
                        }
                    },
                    error: function() {
                        alert('Błąd połączenia. Spróbuj ponownie.');
                        $button.prop('disabled', false).text('Zapisz');
                    }
                });
            });

            // Cancel price edit
            $(document).on('click', '.obraz-cancel-price', function() {
                var $button = $(this);
                var $cell = $button.closest('td');
                var $editForm = $cell.find('.obraz-price-edit');
                var $display = $cell.find('.obraz-price-display');
                var $editButton = $cell.find('.obraz-edit-price');

                // Remove edit form
                $editForm.remove();

                // Show display and button
                $display.show();
                $editButton.show();
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

/**
 * AJAX handler for updating price inline
 */
function update_obraz_price_ajax() {
    check_ajax_referer('obraz_price_nonce', 'nonce');

    if (!current_user_can('edit_posts')) {
        wp_send_json_error('Brak uprawnień');
    }

    $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
    $price = isset($_POST['price']) ? sanitize_text_field($_POST['price']) : '';

    if (!$post_id) {
        wp_send_json_error('Nieprawidłowy ID posta');
    }

    // Get existing ACF field group
    $o_obrazie = get_field('o_obrazie', $post_id);
    if (!is_array($o_obrazie)) {
        $o_obrazie = [];
    }

    // Update only the price field
    $o_obrazie['cena_obrazu'] = $price;

    // Save back to ACF
    update_field('o_obrazie', $o_obrazie, $post_id);

    wp_send_json_success([
        'message' => 'Cena zaktualizowana',
        'price' => $price
    ]);
}
add_action('wp_ajax_update_obraz_price', 'update_obraz_price_ajax');
