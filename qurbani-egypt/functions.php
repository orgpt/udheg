<?php
/**
 * Theme setup and WooCommerce helpers.
 *
 * @package Qurbani_Egypt
 */

if (! defined('ABSPATH')) {
    exit;
}

define('QE_THEME_VERSION', '1.1.0');

function qe_sanitize_checkbox($value): bool
{
    return (bool) $value;
}

function qe_campaign_defaults(): array
{
    return [
        'enabled'              => false,
        'banner_text'          => 'استعد لعيد الأضحى واحجز مبكراً قبل اكتمال السعة.',
        'hero_eyebrow'         => 'حجز أضاحي العيد في مصر',
        'hero_title'           => 'احجز أضحيتك بسهولة وأمان',
        'hero_body'            => 'اختر النوع والوزن، أضف الخدمات المناسبة، وأكمل طلبك من الموقع مع تأكيد واضح لكل التفاصيل.',
        'primary_cta_label'    => 'احجز الآن',
        'secondary_cta_label'  => 'عرض السلة',
        'section_kicker'       => 'الأكثر طلباً في موسم العيد',
        'featured_product_ids' => '',
    ];
}

function qe_campaign_enabled(): bool
{
    return (bool) get_theme_mod('qe_campaign_enabled', qe_campaign_defaults()['enabled']);
}

function qe_campaign_text(string $key): string
{
    $defaults = qe_campaign_defaults();

    return (string) get_theme_mod('qe_campaign_' . $key, $defaults[$key] ?? '');
}

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
        'eidDate'  => (string) get_theme_mod('qe_eid_date', '2026-05-27T00:00:00+03:00'),
        'currency' => function_exists('get_woocommerce_currency_symbol') ? get_woocommerce_currency_symbol() : 'ج.م',
    ]);
}
add_action('wp_enqueue_scripts', 'qe_enqueue_assets');

function qe_body_classes(array $classes): array
{
    $classes[] = 'qe-rtl';

    if (qe_campaign_enabled()) {
        $classes[] = 'qe-campaign-mode';
    }

    return $classes;
}
add_filter('body_class', 'qe_body_classes');

function qe_shop_url(): string
{
    if (function_exists('wc_get_page_permalink')) {
        $url = wc_get_page_permalink('shop');
        if (! empty($url)) {
            return $url;
        }
    }

    return home_url('/#categories');
}

function qe_cart_url(): string
{
    if (function_exists('wc_get_cart_url')) {
        return wc_get_cart_url();
    }

    return home_url('/#categories');
}

function qe_checkout_url(): string
{
    if (function_exists('wc_get_checkout_url')) {
        return wc_get_checkout_url();
    }

    if (function_exists('wc_get_page_permalink')) {
        $url = wc_get_page_permalink('checkout');
        if (! empty($url)) {
            return $url;
        }
    }

    return home_url('/checkout');
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

function qe_campaign_product_ids(): array
{
    $raw = (string) get_theme_mod('qe_campaign_featured_product_ids', qe_campaign_defaults()['featured_product_ids']);
    if ($raw === '') {
        return [];
    }

    $parts = array_map('trim', explode(',', $raw));
    $ids   = array_map('absint', $parts);

    return array_values(array_filter($ids));
}

function qe_product_cards(): array
{
    if (function_exists('wc_get_products')) {
        $ids   = qe_campaign_product_ids();
        $query = [
            'status' => 'publish',
            'limit'  => 3,
        ];

        if (! empty($ids)) {
            $query['include'] = $ids;
            $query['orderby'] = 'post__in';
        }

        $products = wc_get_products($query);

        if (empty($products)) {
            $products = wc_get_products([
                'status'  => 'publish',
                'limit'   => 3,
                'orderby' => 'date',
                'order'   => 'DESC',
            ]);
        }

        if (! empty($products)) {
            return array_map(
                static function ($product): array {
                    $stock_label = 'متاح للحجز';

                    if ($product->managing_stock()) {
                        $stock_quantity = (int) $product->get_stock_quantity();
                        $stock_label    = $stock_quantity > 0 ? sprintf('متبقي %d فقط', $stock_quantity) : 'نفد المخزون';
                    } elseif (! $product->is_in_stock()) {
                        $stock_label = 'نفد المخزون';
                    }

                    $image = get_the_post_thumbnail_url($product->get_id(), 'large');
                    if (! $image) {
                        $image = qe_asset_image('sheep');
                    }

                    return [
                        'title'       => $product->get_name(),
                        'image'       => $image,
                        'price'       => wp_strip_all_tags($product->get_price_html() ?: wc_price((float) $product->get_price())),
                        'stock'       => $stock_label,
                        'url'         => get_permalink($product->get_id()),
                        'button_text' => 'عرض التفاصيل',
                    ];
                },
                $products
            );
        }
    }

    return [
        [
            'title'       => 'خرفان',
            'image'       => qe_asset_image('sheep'),
            'price'       => 'من ٩,٥٠٠ ج.م',
            'stock'       => 'متبقي ٧ فقط',
            'url'         => qe_shop_url(),
            'button_text' => 'ابدأ الطلب',
        ],
        [
            'title'       => 'عجول',
            'image'       => qe_asset_image('cow'),
            'price'       => 'من ٥٨,٠٠٠ ج.م',
            'stock'       => 'متبقي ٣ فقط',
            'url'         => qe_shop_url(),
            'button_text' => 'ابدأ الطلب',
        ],
        [
            'title'       => 'ماعز',
            'image'       => qe_asset_image('goat'),
            'price'       => 'من ٧,٨٠٠ ج.م',
            'stock'       => 'متبقي ٥ فقط',
            'url'         => qe_shop_url(),
            'button_text' => 'ابدأ الطلب',
        ],
    ];
}

function qe_checkout_fields($fields)
{
    if (! is_array($fields)) {
        return $fields;
    }

    $fields['billing']['billing_first_name']['label']             = 'الاسم';
    $fields['billing']['billing_phone']['label']                  = 'الهاتف';
    $fields['billing']['billing_address_1']['label']              = 'العنوان';
    $fields['billing']['billing_address_1']['placeholder']        = 'اكتب العنوان بالتفصيل';
    $fields['order']['qe_slaughter_date'] = [
        'type'     => 'date',
        'label'    => 'تاريخ الذبح',
        'required' => true,
        'class'    => ['form-row-wide'],
        'priority' => 15,
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

function qe_order_received_next_step($order_id): void
{
    if (! $order_id) {
        return;
    }

    echo '<p><a class="qe-button qe-button--gold" href="' . esc_url(qe_shop_url()) . '">متابعة التسوق</a></p>';
}
add_action('woocommerce_thankyou', 'qe_order_received_next_step', 20);

function qe_customize_register(WP_Customize_Manager $wp_customize): void
{
    $wp_customize->add_section('qe_campaign', [
        'title'       => 'Qurbani Campaign',
        'description' => 'إعدادات موسم العيد والمحتوى التسويقي الرئيسي.',
        'priority'    => 35,
    ]);

    $wp_customize->add_setting('qe_campaign_enabled', [
        'default'           => qe_campaign_defaults()['enabled'],
        'sanitize_callback' => 'qe_sanitize_checkbox',
    ]);

    $wp_customize->add_control('qe_campaign_enabled', [
        'label'   => 'تفعيل وضع حملة العيد',
        'section' => 'qe_campaign',
        'type'    => 'checkbox',
    ]);

    foreach ([
        'banner_text'          => 'نص الشريط العلوي',
        'hero_eyebrow'         => 'النص الصغير أعلى العنوان',
        'hero_title'           => 'عنوان البانر الرئيسي',
        'hero_body'            => 'وصف البانر الرئيسي',
        'primary_cta_label'    => 'نص الزر الرئيسي',
        'secondary_cta_label'  => 'نص الزر الثانوي',
        'section_kicker'       => 'عنوان قسم المنتجات',
        'featured_product_ids' => 'معرفات المنتجات المميزة',
    ] as $key => $label) {
        $wp_customize->add_setting('qe_campaign_' . $key, [
            'default'           => qe_campaign_defaults()[$key],
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $wp_customize->add_control('qe_campaign_' . $key, [
            'label'       => $label,
            'section'     => 'qe_campaign',
            'type'        => 'text',
            'description' => $key === 'featured_product_ids' ? 'اكتب IDs المنتجات مفصولة بفاصلة مثل: 12,15,18' : '',
        ]);
    }

    $wp_customize->add_setting('qe_eid_date', [
        'default'           => '2026-05-27T00:00:00+03:00',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('qe_eid_date', [
        'label'       => 'تاريخ العد التنازلي للعيد',
        'description' => 'مثال: 2026-05-27T00:00:00+03:00',
        'section'     => 'qe_campaign',
        'type'        => 'text',
    ]);
}
add_action('customize_register', 'qe_customize_register');
