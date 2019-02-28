<?php

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart'); ?>

<form class="woocommerce-cart-form w-100 mb-5" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
    <?php do_action('woocommerce_before_cart_table'); ?>

    <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents w-100" cellspacing="0">
        <thead>
        <tr>
            <th class="product-thumbnail">Наименование товара</th>
            <th class="product-articulus">Артикул</th>
            <th class="product-price">Цена</th>
            <th class="product-subtotal">Кол-во</th>
            <th class="product-subtotal">Сумма</th>
            <th class="product-close"></th>
        </tr>
        </thead>
        <tbody>
        <?php do_action('woocommerce_before_cart_contents'); ?>

        <?php
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
            $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

            if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                ?>
                <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                    <td class="product-thumbnail">
                        <? $image = wp_prepare_attachment_for_js($_product->get_image_id()); ?>
                        <a href="<?= $product_permalink ?>" class="d-flex">
                            <div class="photo" style="background-image: url('<?= $image['url'] ?>')"></div>
                            <p class="product-name"><?= $_product->get_name() ?></p>
                        </a>
                    </td>
                    <td class="product-articulus"><?= $_product->get_sku() ?></td>
                    <td class="product-price">
                        <?php
                        echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // PHPCS: XSS ok.
                        ?>
                    </td>
                    <td class="product-quantity">
                        <?php
                        if ($_product->is_sold_individually()) {
                            $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                        } else {
                            $product_quantity = woocommerce_quantity_input(array(
                                'input_name' => "cart[{$cart_item_key}][qty]",
                                'input_value' => $cart_item['quantity'],
                                'max_value' => $_product->get_max_purchase_quantity(),
                                'min_value' => '0',
                                'product_name' => $_product->get_name(),
                            ), $_product, false);
                        }

                        echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
                        ?>
                    </td>
                    <td class="product-subtotal">
                        <?php
                        echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // PHPCS: XSS ok.
                        ?>
                    </td>
                    <td class="product-remove">
                        <?php
                        // @codingStandardsIgnoreLine
                        echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
                            '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="fal fa-times"></i></a>',
                            esc_url(wc_get_cart_remove_url($cart_item_key)),
                            __('Remove this item', 'woocommerce'),
                            esc_attr($product_id),
                            esc_attr($_product->get_sku())
                        ), $cart_item_key);
                        ?>
                    </td>
                </tr>
                <?php
            }
        }
        ?>

        <?php do_action('woocommerce_cart_contents'); ?>

        <tr class="d-none">
            <td colspan="6" class="actions">

                <?php if (wc_coupons_enabled()) { ?>
                    <div class="coupon">
                        <label for="coupon_code"><?php esc_html_e('Coupon:', 'woocommerce'); ?></label> <input
                                type="text" name="coupon_code" class="input-text" id="coupon_code" value=""
                                placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>"/>
                        <button type="submit" class="button" name="apply_coupon"
                                value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>"><?php esc_attr_e('Apply coupon', 'woocommerce'); ?></button>
                        <?php do_action('woocommerce_cart_coupon'); ?>
                    </div>
                <?php } ?>

                <button type="submit" class="button" name="update_cart"
                        value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>"><?php esc_html_e('Update cart', 'woocommerce'); ?></button>

                <?php do_action('woocommerce_cart_actions'); ?>

                <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
            </td>
        </tr>

        <?php do_action('woocommerce_after_cart_contents'); ?>
        </tbody>
    </table>
    <div class="actions">
        <p class="totals">Итого: <span class="price">2400 руб.</span></p>
        <hr>
        <div class="buttons">
            <a href="<?= get_permalink( wc_get_page_id( 'shop' ) ); ?>" class="btn-white">Вернуться к покупкам</a>
            <a href="<?= esc_url( wc_get_checkout_url() );?>" class="btn-green">Продолжить оформление</a>
        </div>
    </div>
    <?php do_action('woocommerce_after_cart_table'); ?>
</form>

<div class="cart-collaterals d-none">
    <?php
    /**
     * Cart collaterals hook.
     *
     * @hooked woocommerce_cross_sell_display
     * @hooked woocommerce_cart_totals - 10
     */
    do_action('woocommerce_cart_collaterals');
    ?>
</div>

<?php do_action('woocommerce_after_cart'); ?>
