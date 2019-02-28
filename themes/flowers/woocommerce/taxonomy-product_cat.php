<?php

defined('ABSPATH') || exit;

global $category;
$category = get_queried_object();

if ($category->parent !== 0) {
    $category = get_term($category->parent);
}
get_header('shop');
?>
    <div class="row flex-column-reverse flex-lg-row align-items-center align-items-lg-start mb-5">
        <? get_sidebar() ?>
        <div class="col-xl-9 col-lg-8 pt-3">
            <div class="notices-area w-100"><? wc_print_notices() ?></div>
            <? wc_get_template_part('content', 'product_cat'); ?>
            <? woocommerce_catalog_ordering() ?>
            <?
            if (woocommerce_product_loop()) {
                if (wc_get_loop_prop('total')) :?>
                    <ul class="products">
                    <? while (have_posts()) {
                        the_post();
                        wc_get_template_part('content', 'product');
                    }
                endif; ?>
                </ul>
                <?
            } else {
                do_action('woocommerce_no_products_found');
            } ?>
            <div class="bottom-line">
                <? woocommerce_pagination() ?>
                <hr>
                <p class="pagination-count"><?= $Flowers->woo->paginationText() ?></p>
            </div>
        </div>
    </div>
<? $Flowers->views()->sales() ?>
<? $Flowers->views()->advantages() ?>
<? get_footer('shop');
