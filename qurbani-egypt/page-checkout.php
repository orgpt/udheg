<?php
/**
 * Template Name: Qurbani Checkout
 *
 * @package Qurbani_Egypt
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="qe-checkout-wrap">
    <div class="qe-container">
        <div class="qe-section-head">
            <div>
                <p class="qe-section-kicker">طلب آمن وسريع</p>
                <h1 class="qe-section-title">إتمام الحجز</h1>
                <p class="qe-section-copy">بيانات قليلة، تأكيد مباشر، ومتابعة الطلب من خلال الموقع.</p>
            </div>
        </div>

        <div class="qe-checkout-layout">
            <section class="qe-checkout-card">
                <?php
                if (function_exists('WC') && shortcode_exists('woocommerce_checkout')) {
                    echo do_shortcode('[woocommerce_checkout]');
                } else {
                    ?>
                    <form class="qe-form-grid">
                        <div class="qe-field">
                            <label for="qe-name">الاسم</label>
                            <input id="qe-name" type="text" placeholder="اسم حضرتك">
                        </div>
                        <div class="qe-field">
                            <label for="qe-phone">الهاتف</label>
                            <input id="qe-phone" type="tel" placeholder="رقم الموبايل">
                        </div>
                        <div class="qe-field">
                            <label for="qe-address">العنوان</label>
                            <textarea id="qe-address" rows="3" placeholder="العنوان بالتفصيل"></textarea>
                        </div>
                        <div class="qe-field">
                            <label for="qe-date">تاريخ الذبح</label>
                            <input id="qe-date" type="date">
                        </div>
                        <div class="qe-payment-grid" aria-label="طرق الدفع">
                            <label class="qe-check-option"><input type="radio" name="payment" checked> كاش</label>
                            <label class="qe-check-option"><input type="radio" name="payment"> تحويل</label>
                            <label class="qe-check-option"><input type="radio" name="payment"> محفظة</label>
                        </div>
                        <button type="submit" class="qe-button qe-button--gold">تأكيد الطلب من الموقع</button>
                    </form>
                    <?php
                }
                ?>
            </section>

            <aside class="qe-trust-stack">
                <div class="qe-trust-panel">
                    <strong>ذبح شرعي مضمون</strong>
                    <span>بنأكد كل تفاصيل الذبح قبل التنفيذ.</span>
                </div>
                <div class="qe-trust-panel">
                    <strong>توثيق واضح</strong>
                    <span>تقدر تتابع تفاصيل الطلب من صفحة الطلب بعد الإتمام.</span>
                </div>
                <div class="qe-trust-panel">
                    <strong>توصيل منظم</strong>
                    <span>تنسيق مسبق لموعد ومكان الاستلام.</span>
                </div>
            </aside>
        </div>
    </div>
</main>

<?php
get_footer();
