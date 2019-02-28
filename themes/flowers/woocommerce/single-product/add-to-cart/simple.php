<?php


defined('ABSPATH') || exit;

global $product;
?>
<form class="cart content"
      action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>"
      method="post" enctype='multipart/form-data'>
    <div class="excerpt"><? if (has_excerpt()) the_excerpt() ?></div>
    <p class="price"><?= wc_price($product->get_price()) ?></p>
    <?
    if ($product->is_in_stock()) {
        woocommerce_quantity_input(array(
            'min_value' => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
            'max_value' => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
            'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
        ));
    }
    ?>
   <? wc_get_template('single-product/product-attributes.php') ?>
    <? if ($product->is_in_stock()) : ?>
        <button type="submit" name="add-to-cart" value="<?= esc_attr($product->get_id()); ?>"
                class="add-to-cart btn-green single_add_to_cart_button button alt"><?= esc_html($product->single_add_to_cart_text()); ?></button>
    <? endif ?>
</form>
