<?php
/**
 * Homepage template.
 *
 * @package Qurbani_Egypt
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary">
    <section class="qe-hero">
        <div class="qe-container qe-hero-inner">
            <div class="qe-hero-copy">
                <p class="qe-eyebrow">حجز أضاحي العيد في مصر</p>
                <h1>احجز أضحيتك بسهولة وأمان</h1>
                <p>اختار النوع والوزن، أكد الطلب على واتساب، واستلم توثيق الذبح والتوصيل لحد باب البيت من غير لف كتير.</p>
                <div class="qe-hero-actions">
                    <a class="qe-button qe-button--gold" href="#categories">احجز الآن</a>
                    <a class="qe-button qe-button--ghost" href="<?php echo esc_url(qe_whatsapp_link()); ?>" target="_blank" rel="noopener">اسأل على واتساب</a>
                </div>
                <div class="qe-trust-row" aria-label="علامات الثقة">
                    <div class="qe-trust-badge"><span class="qe-icon">✓</span> ذبح شرعي</div>
                    <div class="qe-trust-badge"><span class="qe-icon">▶</span> توثيق فيديو</div>
                    <div class="qe-trust-badge"><span class="qe-icon">⌂</span> توصيل حتى باب المنزل</div>
                </div>
            </div>
        </div>
    </section>

    <section id="categories" class="qe-section">
        <div class="qe-container">
            <div class="qe-section-head">
                <div>
                    <p class="qe-section-kicker">الأكثر طلبا</p>
                    <h2 class="qe-section-title">اختار أضحيتك</h2>
                    <p class="qe-section-copy">أسعار واضحة، مخزون محدود، وتأكيد سريع بعد الحجز.</p>
                </div>
                <div class="qe-countdown" data-countdown aria-label="العد التنازلي للعيد">
                    <div><strong data-days>0</strong><span>يوم</span></div>
                    <div><strong data-hours>0</strong><span>ساعة</span></div>
                    <div><strong data-minutes>0</strong><span>دقيقة</span></div>
                    <div><strong data-seconds>0</strong><span>ثانية</span></div>
                </div>
            </div>

            <div class="qe-card-grid">
                <?php foreach (qe_product_cards() as $card) : ?>
                    <article class="qe-category-card">
                        <img src="<?php echo esc_url($card['image']); ?>" alt="<?php echo esc_attr($card['title']); ?>">
                        <div class="qe-card-body">
                            <h3><?php echo esc_html($card['title']); ?></h3>
                            <span class="qe-price"><?php echo esc_html($card['price']); ?></span>
                            <br>
                            <span class="qe-stock"><?php echo esc_html($card['stock']); ?></span>
                            <p>حجز سريع مع اختيار الوزن والخدمات قبل التأكيد.</p>
                            <a class="qe-button" href="<?php echo esc_url(qe_whatsapp_link('السلام عليكم، عايز أحجز ' . $card['title'] . '.')); ?>" target="_blank" rel="noopener">احجز <?php echo esc_html($card['title']); ?></a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="steps" class="qe-section qe-section--soft">
        <div class="qe-container">
            <div class="qe-section-head">
                <div>
                    <p class="qe-section-kicker">٣ خطوات بس</p>
                    <h2 class="qe-section-title">خطوات الطلب</h2>
                </div>
            </div>
            <div class="qe-steps">
                <article class="qe-step">
                    <span class="qe-step-number">١</span>
                    <h3>اختيار</h3>
                    <p>اختار النوع والوزن المناسب والكمية المتاحة.</p>
                </article>
                <article class="qe-step">
                    <span class="qe-step-number">٢</span>
                    <h3>دفع</h3>
                    <p>ادفع بالطريقة الأسهل: كاش، تحويل، أو محفظة.</p>
                </article>
                <article class="qe-step">
                    <span class="qe-step-number">٣</span>
                    <h3>ذبح وتوصيل</h3>
                    <p>ذبح شرعي مع توثيق فيديو وتوصيل منظم.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="qe-section">
        <div class="qe-container">
            <div class="qe-section-head">
                <div>
                    <p class="qe-section-kicker">آراء العملاء</p>
                    <h2 class="qe-section-title">ناس حجزت واطمنت</h2>
                </div>
            </div>
            <div class="qe-reviews" aria-label="تقييمات العملاء">
                <article class="qe-review">
                    <div class="qe-stars">★★★★★</div>
                    <blockquote>التعامل كان محترم، الفيديو وصل في معاده والتوصيل كان مظبوط.</blockquote>
                    <cite>أحمد من مدينة نصر</cite>
                </article>
                <article class="qe-review">
                    <div class="qe-stars">★★★★★</div>
                    <blockquote>أول مرة أحجز أضحية أونلاين وكنت قلقان، بس كل حاجة كانت واضحة.</blockquote>
                    <cite>مريم من المعادي</cite>
                </article>
                <article class="qe-review">
                    <div class="qe-stars">★★★★★</div>
                    <blockquote>اختيار الوزن والتقطيع وفر علينا وقت كبير يوم العيد.</blockquote>
                    <cite>خالد من أكتوبر</cite>
                </article>
            </div>
        </div>
    </section>

    <section id="faq" class="qe-section qe-section--soft">
        <div class="qe-container">
            <div class="qe-section-head">
                <div>
                    <p class="qe-section-kicker">أسئلة مهمة</p>
                    <h2 class="qe-section-title">اطمن قبل ما تحجز</h2>
                </div>
            </div>
            <div class="qe-faq">
                <details open>
                    <summary>هل الذبح شرعي؟</summary>
                    <p>نعم، الذبح بيتم حسب الضوابط الشرعية وتقدر تطلب توثيق فيديو.</p>
                </details>
                <details>
                    <summary>هل في توصيل للبيت؟</summary>
                    <p>متاح توصيل حسب المنطقة، وهنأكد مع حضرتك الموعد والتكلفة قبل الدفع النهائي.</p>
                </details>
                <details>
                    <summary>ممكن أختار التقطيع والتغليف؟</summary>
                    <p>أيوه، تقدر تختار تقطيع وتغليف وتوصيل أثناء الحجز.</p>
                </details>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
