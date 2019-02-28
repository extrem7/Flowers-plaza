<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $product;
$product = wc_get_product($post);

get_header('shop'); ?>
    <div class="row flex-column-reverse flex-lg-row align-items-center align-items-lg-start mb-5">
        <? get_sidebar() ?>
        <div class="col-xl-9 col-lg-8 pt-3">
            <div class="notices-area"><? wc_print_notices() ?></div>
            <h2 class="section-title mb-3"><? the_title() ?></h2>
            <div class="d-flex align-items-center flex-column flex-xl-row">
                <div class="gallery">
                    <? $img = get_the_post_thumbnail_url() ?>
                    <a data-fancybox="gallery" class="main-photo" href="<?= $img ?>"
                       style="background-image: url('<?= $img ?>')"></a>
                    <? if ($product->get_gallery_image_ids()): ?>
                        <div class="thumbnails owl-carousel">
                            <? wc_get_template('single-product/product-thumbnails.php') ?>
                        </div>
                    <? endif; ?>
                </div>
                <? woocommerce_simple_add_to_cart() ?>
            </div>
            <div class="description"><?= apply_filters('the_content', wpautop(get_post_field('post_content', $id), true)); ?></div>
            <hr class="mt-4 mb-4">
            <? wc_get_template('single-product/related.php') ?>
        </div>
    </div>
<? $Flowers->views()->sales() ?>
<? $Flowers->views()->advantages() ?>
<? get_footer('shop');
