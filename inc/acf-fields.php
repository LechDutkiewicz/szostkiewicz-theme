<?php
/**
 * ACF Field Groups Registration
 *
 * @package Hello_Theme_Child
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register ACF field groups programmatically
 */
add_action('acf/include_fields', function() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    // Field Group: Informacje o obrazie
    acf_add_local_field_group([
        'key' => 'group_665212497d01e',
        'title' => 'Informacje o obrazie',
        'fields' => [
            [
                'key' => 'field_6860202f58c31',
                'label' => 'Wyróżnione na głównej',
                'name' => 'wyroznione_na_glownej',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'wrapper' => [
                    'width' => '100',
                ],
                'admin_column_enabled' => 1,
                'admin_column_width' => '100px',
                'default_value' => 0,
                'ui' => 0,
            ],
            [
                'key' => 'field_66521246d7b49',
                'label' => 'Galeria zdjęć z obrazem',
                'name' => 'galeria_obrazu',
                'type' => 'gallery',
                'instructions' => '',
                'required' => 0,
                'wrapper' => [
                    'width' => '40',
                ],
                'return_format' => 'array',
                'library' => 'all',
                'insert' => 'append',
                'preview_size' => 'medium',
            ],
            [
                'key' => 'field_686021e5c60ef',
                'label' => 'O obrazie',
                'name' => 'o_obrazie',
                'type' => 'group',
                'instructions' => '',
                'required' => 0,
                'wrapper' => [
                    'width' => '60',
                ],
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_6652126bd7b4a',
                        'label' => 'Wymiary obrazu',
                        'name' => 'wymiary_obrazu',
                        'type' => 'text',
                        'wrapper' => [
                            'width' => '33',
                        ],
                        'placeholder' => 'np. 115 x 75 cm',
                    ],
                    [
                        'key' => 'field_66521282d7b4b',
                        'label' => 'Cena obrazu',
                        'name' => 'cena_obrazu',
                        'type' => 'text',
                        'wrapper' => [
                            'width' => '33',
                        ],
                        'admin_column_enabled' => 1,
                        'placeholder' => 'np. 899',
                    ],
                    [
                        'key' => 'field_rok_powstania',
                        'label' => 'Rok powstania',
                        'name' => 'rok_powstania',
                        'type' => 'date_picker',
                        'wrapper' => [
                            'width' => '33',
                        ],
                        'display_format' => 'Y',
                        'return_format' => 'Y',
                        'first_day' => 1,
                    ],
                    [
                        'key' => 'field_technika',
                        'label' => 'Technika',
                        'name' => 'technika',
                        'type' => 'text',
                        'wrapper' => [
                            'width' => '50',
                        ],
                        'placeholder' => 'np. olej na płótnie',
                    ],
                    [
                        'key' => 'field_sygnatura',
                        'label' => 'Sygnatura',
                        'name' => 'sygnatura',
                        'type' => 'text',
                        'wrapper' => [
                            'width' => '50',
                        ],
                        'placeholder' => 'np. podpisany z przodu',
                    ],
                    [
                        'key' => 'field_68602223c60f0',
                        'label' => 'Opis obrazu',
                        'name' => 'opis_obrazu',
                        'type' => 'wysiwyg',
                        'instructions' => '',
                        'required' => 0,
                        'wrapper' => [
                            'width' => '',
                        ],
                        'tabs' => 'text',
                        'media_upload' => 0,
                        'toolbar' => 'full',
                        'delay' => 0,
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'obraz',
                ],
            ],
        ],
        'menu_order' => 0,
        'position' => 'acf_after_title',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
        'show_in_rest' => 0,
    ]);

    // Options Page: Ustawienia kontaktu
    acf_add_options_page([
        'page_title' => 'Ustawienia kontaktu',
        'menu_title' => 'Kontakt',
        'menu_slug' => 'ustawienia-kontaktu',
        'capability' => 'edit_posts',
        'icon_url' => 'dashicons-email',
        'redirect' => false,
    ]);

    // Field Group: Kontakt - Messenger i WhatsApp
    acf_add_local_field_group([
        'key' => 'group_contact_settings',
        'title' => 'Ustawienia kontaktu',
        'fields' => [
            [
                'key' => 'field_messenger_link',
                'label' => 'Link do Messengera',
                'name' => 'messenger_link',
                'type' => 'url',
                'instructions' => 'Wklej link do swojego profilu Messenger (np. https://m.me/twojaprofil)',
                'required' => 0,
                'placeholder' => 'https://m.me/twojaprofil',
            ],
            [
                'key' => 'field_whatsapp_link',
                'label' => 'Numer WhatsApp',
                'name' => 'whatsapp_number',
                'type' => 'text',
                'instructions' => 'Wpisz numer telefonu z kodem kraju (np. 48123456789)',
                'required' => 0,
                'placeholder' => '48123456789',
            ],
            [
                'key' => 'field_enable_jquery_frontend',
                'label' => 'Włącz jQuery na froncie',
                'name' => 'enable_jquery_frontend',
                'type' => 'true_false',
                'instructions' => 'Zaznacz to pole tylko jeśli używasz komponentów Elementora, które wymagają jQuery (np. niektóre widgety). Domyślnie jQuery jest wyłączone dla lepszej wydajności.',
                'required' => 0,
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Włączone',
                'ui_off_text' => 'Wyłączone',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'ustawienia-kontaktu',
                ],
            ],
        ],
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'active' => true,
    ]);
});
