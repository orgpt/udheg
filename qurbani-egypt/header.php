<?php
/**
 * Header template.
 *
 * @package Qurbani_Egypt
 */

if (! defined('ABSPATH')) {
    exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="qe-site-header">
    <div class="qe-container qe-header-inner">
        <a class="qe-logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php bloginfo('name'); ?>">
            <?php if (has_custom_logo()) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <span class="qe-logo-mark">ق</span>
                <span><?php echo esc_html(get_bloginfo('name') ?: 'أضحيتي'); ?></span>
            <?php endif; ?>
        </a>

        <nav class="qe-nav" aria-label="القائمة الرئيسية">
            <?php
            if (has_nav_menu('primary')) {
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container'      => false,
                    'items_wrap'     => '%3$s',
                    'depth'          => 1,
                ]);
            } else {
                echo '<a href="' . esc_url(home_url('/#categories')) . '">الأضاحي</a>';
                echo '<a href="' . esc_url(home_url('/#steps')) . '">طريقة الطلب</a>';
                echo '<a href="' . esc_url(home_url('/#faq')) . '">أسئلة مهمة</a>';
            }
            ?>
        </nav>

        <div class="qe-header-actions">
            <a class="qe-button qe-button--ghost" href="<?php echo esc_url(qe_cart_url()); ?>">السلة</a>
            <a class="qe-button" href="<?php echo esc_url(qe_shop_url()); ?>">اطلب الآن</a>
        </div>
    </div>
</header>
