<?php

defined('ABSPATH') || exit;

global $product, $bigProduct;

if (empty($product) || !$product->is_visible()) {
    return;
}
$post_thumbnail_id = $product->get_image_id();
$img = wp_prepare_attachment_for_js($post_thumbnail_id);

$label = get_field('label');
$labels = [
    'new' => 'Новинка',
    'top' => 'Хит продаж',
    'price' => 'Супер цена'
];
?>
<li <? wc_product_class("product-card $bigProduct"); ?>>
    <a href="<? the_permalink() ?>">
        <? if ($label): ?>
            <div class="label label_<?= $label ?>"><?= $labels[$label] ?></div>
        <? endif; ?>
        <div class="photo" style="background-image:url('<?= $img['url'] ?>') "></div>
        <p class="title"><? the_title() ?></p>
        <p class="price"><?= wc_price($product->get_price()) ?></p>
    </a>
    <? if ($product->is_in_stock()): ?>
        <a data-id="<? the_ID() ?>" href="<?= $product->add_to_cart_url() ?>"
           class="btn-white add-to-cart">Добавить в корзину</a>
    <? endif; ?>
</li>
