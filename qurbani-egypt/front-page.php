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

$campaign_enabled = qe_campaign_enabled();
$hero_eyebrow     = $campaign_enabled ? qe_campaign_text('hero_eyebrow') : 'حجز أضاحي العيد في مصر';
$hero_title       = $campaign_enabled ? qe_campaign_text('hero_title') : 'احجز أضحيتك بسهولة وأمان';
$hero_body        = $campaign_enabled ? qe_campaign_text('hero_body') : 'اختر النوع والوزن، أضف الخدمات المناسبة، وأكمل طلبك من الموقع مع تأكيد واضح لكل التفاصيل.';
$primary_label    = $campaign_enabled ? qe_campaign_text('primary_cta_label') : 'احجز الآن';
$secondary_label  = $campaign_enabled ? qe_campaign_text('secondary_cta_label') : 'عرض السلة';
$section_kicker   = $campaign_enabled ? qe_campaign_text('section_kicker') : 'الأكثر طلباً';
?>

<main id="primary">
    <section class="qe-hero">
        <div class="qe-container qe-hero-inner">
            <div class="qe-hero-copy">
                <?php if ($campaign_enabled && qe_campaign_text('banner_text') !== '') : ?>
                    <div class="qe-campaign-banner"><?php echo esc_html(qe_campaign_text('banner_text')); ?></div>
                <?php endif; ?>
                <p class="qe-eyebrow"><?php echo esc_html($hero_eyebrow); ?></p>
                <h1><?php echo esc_html($hero_title); ?></h1>
                <p><?php echo esc_html($hero_body); ?></p>
                <div class="qe-hero-actions">
                    <a class="qe-button qe-button--gold" href="#categories"><?php echo esc_html($primary_label); ?></a>
                    <a class="qe-button qe-button--ghost" href="<?php echo esc_url(qe_cart_url()); ?>"><?php echo esc_html($secondary_label); ?></a>
                </div>
                <div class="qe-trust-row" aria-label="علامات الثقة">
                    <div class="qe-trust-badge"><span class="qe-icon">✓</span> ذبح شرعي</div>
                    <div class="qe-trust-badge"><span class="qe-icon">▣</span> طلب ومتابعة من الموقع</div>
                    <div class="qe-trust-badge"><span class="qe-icon">⌂</span> توصيل حتى باب المنزل</div>
                </div>
            </div>
        </div>
    </section>

    <section id="categories" class="qe-section">
        <div class="qe-container">
            <div class="qe-section-head">
                <div>
                    <p class="qe-section-kicker"><?php echo esc_html($section_kicker); ?></p>
                    <h2 class="qe-section-title">اختر أضحيتك</h2>
                    <p class="qe-section-copy">أسعار واضحة، مخزون محدث، وإتمام الطلب بالكامل من خلال الموقع.</p>
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
                            <p>حجز سريع مع اختيار الوزن والخدمات قبل إتمام الطلب.</p>
                            <a class="qe-button" href="<?php echo esc_url($card['url'] ?? qe_shop_url()); ?>"><?php echo esc_html($card['button_text'] ?? 'عرض التفاصيل'); ?></a>
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
                    <p>اختر النوع والوزن المناسب والكمية المتاحة.</p>
                </article>
                <article class="qe-step">
                    <span class="qe-step-number">٢</span>
                    <h3>دفع</h3>
                    <p>أضف المنتج للسلة وأكمل الدفع بالطريقة الأنسب لك.</p>
                </article>
                <article class="qe-step">
                    <span class="qe-step-number">٣</span>
                    <h3>ذبح وتوصيل</h3>
                    <p>ذبح شرعي وتنسيق واضح للتوصيل والاستلام.</p>
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
                    <blockquote>التعامل كان محترم، والفيديو وصل في معاده، والتوصيل كان مضبوط.</blockquote>
                    <cite>أحمد من مدينة نصر</cite>
                </article>
                <article class="qe-review">
                    <div class="qe-stars">★★★★★</div>
                    <blockquote>أول مرة أحجز أضحية أونلاين وكنت قلقانة، بس كل حاجة كانت واضحة.</blockquote>
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
                    <p>نعم، الذبح بيتم حسب الضوابط الشرعية وتقدر تراجع تفاصيل الطلب بالكامل من الموقع.</p>
                </details>
                <details>
                    <summary>هل في توصيل للبيت؟</summary>
                    <p>متاح توصيل حسب المنطقة، وهنأكد مع حضرتك الموعد والتكلفة قبل التنفيذ النهائي.</p>
                </details>
                <details>
                    <summary>ممكن أختار التقطيع والتغليف؟</summary>
                    <p>أيوه، تقدر تختار التقطيع والتغليف والتوصيل أثناء الحجز من صفحة المنتج.</p>
                </details>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
