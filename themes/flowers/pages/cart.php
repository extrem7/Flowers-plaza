<? /* Template Name: Корзина */
get_header(); ?>
    <div class="row flex-column-reverse flex-lg-row align-items-center align-items-lg-start mb-5">
        <div class="container">
            <h1 class="section-title mb-3"><? the_title() ?></h1>
            <?= do_shortcode('[woocommerce_cart]') ?>
            <? $Flowers->views()->sales() ?>
            <? $Flowers->views()->advantages() ?>
        </div>
    </div>
<? get_footer() ?>