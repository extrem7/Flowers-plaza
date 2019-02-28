<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$empty = WC()->cart->is_empty();

do_action( 'woocommerce_before_mini_cart' ); ?>
<a href="<?= wc_get_cart_url() ?>" class="mini-cart <?= $empty ? 'disabled' : '' ?>">
    <span>Корзина</span>
    <img src="<?= path() ?>assets/img/icon-cart.png" alt="cart">
    <? if (!$empty): ?>
        <span class="count"><?= WC()->cart->get_cart_contents_count(); ?></span>
    <? endif; ?>
</a>
<?php do_action( 'woocommerce_after_mini_cart' ); ?>
