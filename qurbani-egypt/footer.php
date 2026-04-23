<?php
/**
 * Footer template.
 *
 * @package Qurbani_Egypt
 */

if (! defined('ABSPATH')) {
    exit;
}
?>
<a class="qe-whatsapp-float qe-cart-float" href="<?php echo esc_url(qe_cart_url()); ?>" aria-label="عرض السلة">🛒</a>

<div class="qe-sticky-cta" role="region" aria-label="احجز الآن">
    <div class="qe-sticky-inner qe-container">
        <div>
            <strong>احجز أضحيتك دلوقتي</strong>
            <span>اختيار المنتج وإتمام الطلب بالكامل من الموقع</span>
        </div>
        <a class="qe-button qe-button--gold" href="<?php echo esc_url(qe_shop_url()); ?>">اطلب الآن</a>
    </div>
</div>

<footer class="qe-site-footer">
    <div class="qe-container qe-footer-inner">
        <div>
            <strong><?php echo esc_html(get_bloginfo('name') ?: 'أضحيتي'); ?></strong>
            <p>ذبح شرعي، توثيق واضح، وتوصيل حتى باب المنزل داخل مصر مع طلب كامل من الموقع.</p>
        </div>
        <a class="qe-button qe-button--gold" href="<?php echo esc_url(qe_checkout_url()); ?>">إتمام الطلب</a>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
