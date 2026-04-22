<?php
/**
 * Simplified conversion-focused WooCommerce single product template.
 *
 * @package Qurbani_Egypt
 */

defined('ABSPATH') || exit;

get_header('shop');

global $product;

if (! $product && function_exists('wc_get_product')) {
    $product = wc_get_product(get_the_ID());
}

$price = $product ? (float) $product->get_price() : 9500;
$image = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: qe_asset_image('sheep');
?>

<main class="qe-container qe-product-shell" data-product-booking data-base-price="<?php echo esc_attr((string) $price); ?>">
    <section class="qe-product-gallery">
        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr(get_the_title() ?: 'أضحية'); ?>">
    </section>

    <aside class="qe-product-panel">
        <?php woocommerce_output_all_notices(); ?>
        <p class="qe-section-kicker">حجز مباشر</p>
        <h1><?php the_title(); ?></h1>
        <p class="qe-product-note">اختار الوزن والخدمات، والسعر بيتحدث قدامك قبل تأكيد الطلب.</p>

        <div class="qe-product-price">
            <span data-price-output><?php echo esc_html(number_format_i18n($price)); ?></span>
            <small><?php echo esc_html(function_exists('get_woocommerce_currency_symbol') ? get_woocommerce_currency_symbol() : 'ج.م'); ?></small>
        </div>

        <form class="cart qe-form-grid" method="post" enctype="multipart/form-data">
            <div class="qe-field">
                <label for="qe-weight">الوزن</label>
                <select id="qe-weight" name="qe_weight" data-weight-selector>
                    <option value="1" data-multiplier="1">وزن أساسي</option>
                    <option value="1.15" data-multiplier="1.15">وزن أكبر + ١٥٪</option>
                    <option value="1.3" data-multiplier="1.3">وزن مميز + ٣٠٪</option>
                </select>
            </div>

            <div class="qe-field">
                <label>الخدمات</label>
                <div class="qe-options">
                    <label class="qe-check-option"><input type="checkbox" name="qe_cutting" data-price-addon="350"> تقطيع</label>
                    <label class="qe-check-option"><input type="checkbox" name="qe_packaging" data-price-addon="180"> تغليف</label>
                    <label class="qe-check-option"><input type="checkbox" name="qe_delivery" data-price-addon="250"> توصيل</label>
                </div>
            </div>

            <div class="qe-field">
                <label for="qe-delivery-date">يوم العيد</label>
                <input id="qe-delivery-date" type="date" name="qe_delivery_date">
            </div>

            <?php if ($product) : ?>
                <?php woocommerce_quantity_input(['min_value' => 1, 'max_value' => $product->get_max_purchase_quantity()]); ?>
                <input type="hidden" name="add-to-cart" value="<?php echo esc_attr((string) $product->get_id()); ?>">
                <div class="qe-product-actions">
                    <button type="submit" class="single_add_to_cart_button button alt">إضافة للسلة</button>
                    <a class="qe-button qe-button--gold" href="<?php echo esc_url(qe_whatsapp_link('السلام عليكم، عايز أحجز ' . get_the_title() . '.')); ?>" target="_blank" rel="noopener">طلب سريع واتساب</a>
                </div>
            <?php else : ?>
                <div class="qe-product-actions">
                    <a class="qe-button qe-button--gold" href="<?php echo esc_url(qe_whatsapp_link('السلام عليكم، عايز أحجز أضحية.')); ?>" target="_blank" rel="noopener">طلب سريع واتساب</a>
                </div>
            <?php endif; ?>
        </form>

        <div class="qe-trust-stack">
            <div class="qe-trust-panel">
                <strong>فيديو الذبح</strong>
                <span>توثيق واضح يطمنك بعد التنفيذ.</span>
            </div>
            <div class="qe-trust-panel">
                <strong>ضمان وشفافية</strong>
                <span>تأكيد الوزن والخدمات قبل الدفع النهائي.</span>
            </div>
        </div>
    </aside>
</main>

<?php
get_footer('shop');
