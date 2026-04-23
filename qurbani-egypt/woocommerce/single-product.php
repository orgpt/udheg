<?php
/**
 * Conversion-focused WooCommerce single product template.
 *
 * @package Qurbani_Egypt
 */

defined('ABSPATH') || exit;

get_header('shop');

global $product;

if ((! is_object($product) || ! method_exists($product, 'get_price')) && function_exists('wc_get_product')) {
    $product = wc_get_product(get_the_ID());
}

$price        = (is_object($product) && method_exists($product, 'get_price')) ? (float) $product->get_price() : 9500;
$config       = qe_product_booking_config($product);
$types        = $config['types'];
$weights      = $config['weights'];
$base_image   = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: $config['fallback_image'];
$default_type = (string) array_key_first($types);
?>

<main class="qe-container qe-product-shell" data-product-booking data-base-price="<?php echo esc_attr((string) $price); ?>">
    <section class="qe-product-gallery">
        <img src="<?php echo esc_url($base_image); ?>" alt="<?php echo esc_attr(get_the_title() ?: 'أضحية'); ?>" data-product-image>
    </section>

    <aside class="qe-product-panel">
        <?php woocommerce_output_all_notices(); ?>
        <p class="qe-section-kicker">حجز مباشر</p>
        <h1><?php the_title(); ?></h1>
        <p class="qe-product-note">اختيار سهل للنوع والوزن والإضافات مع صور واضحة وتحديث فوري للسعر قبل إضافة الطلب للسلة.</p>

        <div class="qe-product-price">
            <span data-price-output><?php echo esc_html(number_format_i18n($price)); ?></span>
            <small><?php echo esc_html(function_exists('get_woocommerce_currency_symbol') ? get_woocommerce_currency_symbol() : 'ج.م'); ?></small>
        </div>

        <form class="cart qe-form-grid" method="post" enctype="multipart/form-data">
            <div class="qe-choice-group">
                <div class="qe-group-head">
                    <label class="qe-group-title">النوع</label>
                    <span class="qe-group-note">كل نوع له صورة وسعر إضافي حسب الاختيار</span>
                </div>
                <div class="qe-visual-grid">
                    <?php foreach ($types as $key => $type) : ?>
                        <label class="qe-visual-option">
                            <input type="radio" name="qe_variant" value="<?php echo esc_attr($key); ?>" data-option-card data-price-addon="<?php echo esc_attr((string) $type['price']); ?>" data-option-image="<?php echo esc_url($type['image']); ?>" <?php checked($key, $default_type); ?>>
                            <span class="qe-visual-card">
                                <img src="<?php echo esc_url($type['image']); ?>" alt="<?php echo esc_attr($type['label']); ?>">
                                <strong><?php echo esc_html($type['label']); ?></strong>
                                <small><?php echo $type['price'] > 0 ? esc_html('+' . number_format_i18n((float) $type['price']) . ' ج.م') : esc_html('بدون زيادة'); ?></small>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="qe-choice-group">
                <div class="qe-group-head">
                    <label class="qe-group-title">الوزن</label>
                    <span class="qe-group-note">اختر الفئة المناسبة وسيتم تحديث السعر فوراً</span>
                </div>
                <div class="qe-option-stack">
                    <?php foreach ($weights as $key => $weight) : ?>
                        <label class="qe-check-option qe-check-option--rich">
                            <input type="radio" name="qe_weight" value="<?php echo esc_attr($key); ?>" data-price-addon="<?php echo esc_attr((string) $weight['price']); ?>" <?php checked($key, (string) array_key_first($weights)); ?>>
                            <span class="qe-option-copy">
                                <strong><?php echo esc_html($weight['label']); ?></strong>
                                <small><?php echo $weight['price'] > 0 ? esc_html('+' . number_format_i18n((float) $weight['price']) . ' ج.م') : esc_html('مشمول في السعر الأساسي'); ?></small>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="qe-choice-group">
                <div class="qe-group-head">
                    <label class="qe-group-title">الإضافات</label>
                    <span class="qe-group-note">الذبح والتغليف والتقطيع خدمات اختيارية برسوم إضافية</span>
                </div>
                <div class="qe-option-stack">
                    <?php foreach (qe_product_booking_options() as $key => $option) : ?>
                        <label class="qe-check-option qe-check-option--rich">
                            <input type="checkbox" name="<?php echo esc_attr($key); ?>" data-price-addon="<?php echo esc_attr((string) $option['price']); ?>">
                            <span class="qe-option-copy">
                                <strong><?php echo esc_html($option['label']); ?></strong>
                                <small><?php echo esc_html('+' . number_format_i18n((float) $option['price']) . ' ج.م'); ?></small>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="qe-choice-group">
                <div class="qe-group-head">
                    <label class="qe-group-title" for="qe-delivery-date">موعد التسليم</label>
                    <span class="qe-group-note">اختيار الموعد بدون أي تكلفة إضافية</span>
                </div>
                <div class="qe-delivery-card">
                    <input id="qe-delivery-date" type="date" name="qe_delivery_date">
                    <p>الخدمة مجانية ويمكن تعديل الموعد لاحقاً قبل التنفيذ.</p>
                </div>
            </div>

            <?php if (is_object($product) && method_exists($product, 'get_id')) : ?>
                <?php woocommerce_quantity_input(['min_value' => 1, 'max_value' => $product->get_max_purchase_quantity()]); ?>
                <input type="hidden" name="add-to-cart" value="<?php echo esc_attr((string) $product->get_id()); ?>">
                <div class="qe-product-actions">
                    <button type="submit" class="single_add_to_cart_button button alt">إضافة للسلة</button>
                    <a class="qe-button qe-button--gold" href="<?php echo esc_url(qe_checkout_url()); ?>">إتمام الطلب</a>
                </div>
            <?php else : ?>
                <div class="qe-product-actions">
                    <a class="qe-button qe-button--gold" href="<?php echo esc_url(qe_shop_url()); ?>">ابدأ الطلب</a>
                </div>
            <?php endif; ?>
        </form>

        <div class="qe-summary-card">
            <div>
                <strong>تجربة حجز سلسة</strong>
                <span>صور للأنواع، تفاصيل واضحة، وموعد تسليم مجاني.</span>
            </div>
            <div>
                <strong>ملخص السعر</strong>
                <span>السعر النهائي يشمل النوع والوزن والإضافات المختارة فقط.</span>
            </div>
        </div>
    </aside>
</main>

<?php
get_footer('shop');
