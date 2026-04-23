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
<a class="qe-whatsapp-float" href="<?php echo esc_url(qe_whatsapp_link()); ?>" target="_blank" rel="noopener" aria-label="اطلب على واتساب">☎</a>

<div class="qe-sticky-cta" role="region" aria-label="احجز الآن">
    <div class="qe-sticky-inner qe-container">
        <div>
            <strong>احجز أضحيتك دلوقتي</strong>
            <span>دفع بسيط وتأكيد سريع على واتساب</span>
        </div>
        <a class="qe-button qe-button--gold" href="<?php echo esc_url(home_url('/#categories')); ?>">احجز الآن</a>
    </div>
</div>

<footer class="qe-site-footer">
    <div class="qe-container qe-footer-inner">
        <div>
            <strong><?php echo esc_html(get_bloginfo('name') ?: 'أضحيتي'); ?></strong>
            <p>ذبح شرعي، توثيق فيديو، وتوصيل حتى باب المنزل داخل مصر.</p>
        </div>
        <a class="qe-button qe-button--gold" href="<?php echo esc_url(qe_whatsapp_link('السلام عليكم، محتاج أعرف تفاصيل حجز الأضحية.')); ?>" target="_blank" rel="noopener">كلمنا على واتساب</a>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
