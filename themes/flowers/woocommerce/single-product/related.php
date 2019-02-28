<?php


if (!defined('ABSPATH')) {
    exit;
}

global $product;

$related_products = wc_get_related_products(get_the_ID());

if ($related_products) : ?>
    <h2 class="section-title mt-3 mb-4">Другие товары этой коллекции:</h2>
    <ul class="products">
        <? foreach ($related_products as $related_product) {
            $post_object = get_post($related_product);
            setup_postdata($GLOBALS['post'] =& $post_object);
            wc_get_template_part('content', 'product');
        } ?>
    </ul>
<? endif;
wp_reset_postdata();
