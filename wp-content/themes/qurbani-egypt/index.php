<?php
/**
 * Default template.
 *
 * @package Qurbani_Egypt
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="qe-section">
    <div class="qe-container">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('qe-trust-panel'); ?>>
                    <h1 class="qe-section-title"><?php the_title(); ?></h1>
                    <div><?php the_content(); ?></div>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <h1 class="qe-section-title">أهلا بيك</h1>
            <p class="qe-section-copy">ابدأ بإضافة صفحة رئيسية أو منتجات WooCommerce للأضاحي.</p>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
