<?php
/**
 * Theme setup and WooCommerce helpers.
 *
 * @package Qurbani_Egypt
 */

if (! defined('ABSPATH')) {
    exit;
}

define('QE_THEME_VERSION', '1.0.0');

function qe_setup(): void
{
    load_theme_textdomain('qurbani-egypt', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height'      => 96,
        'width'       => 240,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    add_theme_support('html5', ['search-form', 'comment-form', 'gallery', 'caption', 'style', 'script']);

    register_nav_menus([
        'primary' => __('Primary menu', 'qurbani-egypt'),
    ]);
}
add_action('after_setup_theme', 'qe_setup');

function qe_enqueue_assets(): void
{
    wp_enqueue_style(
        'qe-fonts',
        'https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap',
        [],
        null
    );

    wp_enqueue_style('qe-style', get_stylesheet_uri(), ['qe-fonts'], QE_THEME_VERSION);
    wp_enqueue_script('qe-theme', get_template_directory_uri() . '/assets/js/theme.js', [], QE_THEME_VERSION, true);

    wp_localize_script('qe-theme', 'qeTheme', [
        'whatsapp' => preg_replace('/\D+/', '', (string) get_theme_mod('qe_whatsapp_number', '201000000000')),
        'eidDate'  => (string) get_theme_mod('qe_eid_date', '2026-05-27T00:00:00+03:00'),
        'currency' => function_exists('get_woocommerce_currency_symbol') ? get_woocommerce_currency_symbol() : 'ج.م',
    ]);
}
add_action('wp_enqueue_scripts', 'qe_enqueue_assets');

function qe_body_classes(array $classes): array
{
    $classes[] = 'qe-rtl';
    return $classes;
}
add_filter('body_class', 'qe_body_classes');

function qe_whatsapp_link(string $message = ''): string
{
    $number = preg_replace('/\D+/', '', (string) get_theme_mod('qe_whatsapp_number', '201000000000'));
    $text   = $message ?: 'السلام عليكم، عايز احجز أضحية.';

    return 'https://wa.me/' . rawurlencode($number) . '?text=' . rawurlencode($text);
}

function qe_asset_image(string $kind): string
{
    $images = [
        'sheep' => 'https://images.unsplash.com/photo-1484557985045-edf25e08da73?auto=format&fit=crop&w=900&q=80',
        'cow'   => 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?auto=format&fit=crop&w=900&q=80',
        'goat'  => 'https://images.unsplash.com/photo-1524024973431-2ad916746881?auto=format&fit=crop&w=900&q=80',
    ];

    return $images[$kind] ?? $images['sheep'];
}

function qe_product_cards(): array
{
    return [
        [
            'title' => 'خرفان',
            'image' => qe_asset_image('sheep'),
            'price' => 'من ٩,٥٠٠ ج.م',
            'stock' => 'متبقي ٧ فقط',
        ],
        [
            'title' => 'عجول',
            'image' => qe_asset_image('cow'),
            'price' => 'من ٥٨,٠٠٠ ج.م',
            'stock' => 'متبقي ٣ فقط',
        ],
        [
            'title' => 'ماعز',
            'image' => qe_asset_image('goat'),
            'price' => 'من ٧,٨٠٠ ج.م',
            'stock' => 'متبقي ٥ فقط',
        ],
    ];
}

function qe_checkout_fields($fields)
{
    if (! is_array($fields)) {
        return $fields;
    }

    $fields['billing']['billing_first_name']['label'] = 'الاسم';
    $fields['billing']['billing_phone']['label'] = 'الهاتف';
    $fields['billing']['billing_address_1']['label'] = 'العنوان';
    $fields['billing']['billing_address_1']['placeholder'] = 'اكتب العنوان بالتفصيل';

    $fields['order']['qe_slaughter_date'] = [
        'type'        => 'date',
        'label'       => 'تاريخ الذبح',
        'required'    => true,
        'class'       => ['form-row-wide'],
        'priority'    => 15,
    ];

    return $fields;
}
add_filter('woocommerce_checkout_fields', 'qe_checkout_fields');

function qe_save_checkout_meta(int $order_id): void
{
    if (! empty($_POST['qe_slaughter_date'])) {
        update_post_meta($order_id, '_qe_slaughter_date', sanitize_text_field(wp_unslash($_POST['qe_slaughter_date'])));
    }
}
add_action('woocommerce_checkout_update_order_meta', 'qe_save_checkout_meta');

function qe_product_booking_options(): array
{
    return [
        'qe_cutting'   => ['label' => 'تقطيع', 'price' => 350],
        'qe_packaging' => ['label' => 'تغليف', 'price' => 180],
        'qe_delivery'  => ['label' => 'توصيل', 'price' => 250],
    ];
}

function qe_add_cart_item_booking_data(array $cart_item_data, int $product_id): array
{
    if (empty($_POST['qe_weight']) && empty($_POST['qe_delivery_date'])) {
        return $cart_item_data;
    }

    $product = function_exists('wc_get_product') ? wc_get_product($product_id) : null;
    $base    = $product ? (float) $product->get_price() : 0.0;
    $weight  = isset($_POST['qe_weight']) ? (float) wp_unslash($_POST['qe_weight']) : 1.0;
    $weight  = in_array($weight, [1.0, 1.15, 1.3], true) ? $weight : 1.0;
    $addons  = [];
    $total   = $base * $weight;

    foreach (qe_product_booking_options() as $key => $option) {
        if (! empty($_POST[$key])) {
            $addons[$key] = $option;
            $total += (float) $option['price'];
        }
    }

    $cart_item_data['qe_booking'] = [
        'weight_multiplier' => $weight,
        'addons'            => $addons,
        'delivery_date'     => isset($_POST['qe_delivery_date']) ? sanitize_text_field(wp_unslash($_POST['qe_delivery_date'])) : '',
        'calculated_price'  => $total,
    ];
    $cart_item_data['qe_unique_key'] = md5(wp_json_encode($cart_item_data['qe_booking']) . microtime());

    return $cart_item_data;
}
add_filter('woocommerce_add_cart_item_data', 'qe_add_cart_item_booking_data', 10, 2);

function qe_apply_cart_item_booking_price(WC_Cart $cart): void
{
    if (is_admin() && ! defined('DOING_AJAX')) {
        return;
    }

    foreach ($cart->get_cart() as $cart_item) {
        if (! empty($cart_item['qe_booking']['calculated_price']) && isset($cart_item['data'])) {
            $cart_item['data']->set_price((float) $cart_item['qe_booking']['calculated_price']);
        }
    }
}
add_action('woocommerce_before_calculate_totals', 'qe_apply_cart_item_booking_price');

function qe_display_cart_item_booking_data(array $item_data, array $cart_item): array
{
    if (empty($cart_item['qe_booking'])) {
        return $item_data;
    }

    $weight_labels = [
        '1'    => 'وزن أساسي',
        '1.15' => 'وزن أكبر + ١٥٪',
        '1.3'  => 'وزن مميز + ٣٠٪',
    ];
    $weight = (string) $cart_item['qe_booking']['weight_multiplier'];

    $item_data[] = [
        'key'   => 'الوزن',
        'value' => $weight_labels[$weight] ?? 'وزن أساسي',
    ];

    if (! empty($cart_item['qe_booking']['addons'])) {
        $item_data[] = [
            'key'   => 'الخدمات',
            'value' => implode('، ', wp_list_pluck($cart_item['qe_booking']['addons'], 'label')),
        ];
    }

    if (! empty($cart_item['qe_booking']['delivery_date'])) {
        $item_data[] = [
            'key'   => 'يوم العيد',
            'value' => esc_html($cart_item['qe_booking']['delivery_date']),
        ];
    }

    return $item_data;
}
add_filter('woocommerce_get_item_data', 'qe_display_cart_item_booking_data', 10, 2);

function qe_save_order_item_booking_data(WC_Order_Item_Product $item, string $cart_item_key, array $values): void
{
    if (empty($values['qe_booking'])) {
        return;
    }

    $booking = $values['qe_booking'];
    $item->add_meta_data('اختيار الوزن', (string) $booking['weight_multiplier']);

    if (! empty($booking['addons'])) {
        $item->add_meta_data('الخدمات', implode('، ', wp_list_pluck($booking['addons'], 'label')));
    }

    if (! empty($booking['delivery_date'])) {
        $item->add_meta_data('يوم العيد', $booking['delivery_date']);
    }
}
add_action('woocommerce_checkout_create_order_line_item', 'qe_save_order_item_booking_data', 10, 3);

function qe_order_received_whatsapp($order_id): void
{
    if (! $order_id) {
        return;
    }

    $message = 'تم استلام طلب الأضحية رقم ' . $order_id . '. هنأكد مع حضرتك التفاصيل على واتساب.';
    echo '<p><a class="qe-button qe-button--gold" href="' . esc_url(qe_whatsapp_link($message)) . '" target="_blank" rel="noopener">تأكيد الطلب على واتساب</a></p>';
}
add_action('woocommerce_thankyou', 'qe_order_received_whatsapp', 20);

function qe_customize_register(WP_Customize_Manager $wp_customize): void
{
    $wp_customize->add_section('qe_conversion', [
        'title'       => 'Qurbani Conversion',
        'description' => 'إعدادات واتساب وتاريخ العيد.',
        'priority'    => 35,
    ]);

    $wp_customize->add_setting('qe_whatsapp_number', [
        'default'           => '201000000000',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('qe_whatsapp_number', [
        'label'   => 'رقم واتساب دولي',
        'section' => 'qe_conversion',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('qe_eid_date', [
        'default'           => '2026-05-27T00:00:00+03:00',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('qe_eid_date', [
        'label'       => 'تاريخ العد التنازلي للعيد',
        'description' => 'مثال: 2026-05-27T00:00:00+03:00',
        'section'     => 'qe_conversion',
        'type'        => 'text',
    ]);
}
add_action('customize_register', 'qe_customize_register');
