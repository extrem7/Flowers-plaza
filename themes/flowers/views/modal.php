<?
global $product, $qty;
$product = wc_get_product($post); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fal fa-times"></i>
        </button>
        <div class="modal-body">
            <h2 class="section-title text-center">Ваша покупка успешно добавлена в корзину</h2>
            <hr class="mb-2 mb-3">
            <div class="product-info">
                <div class="left">
                    <div class="photo" style="background-image: url('<?= get_the_post_thumbnail_url() ?>')"></div>
                    <div class="info">
                        <p class="title"><? the_title() ?></p>
                        <? wc_get_template('single-product/product-attributes.php') ?>
                    </div>
                </div>
                <div class="right">
                    <ul>
                        <li>Цена: <b><?= wc_price($product->get_price()) ?></b></li>
                        <li>Кол-во: <b><?= $qty ?></b></li>
                        <li>сумма: <b><?= wc_price($product->get_price() * $qty) ?></b></li>
                    </ul>
                    <div class="buttons">
                        <a href="" class="btn-white" data-dismiss="modal">Продолжить покупки</a>
                        <a href="<?= wc_get_cart_url() ?>" class="btn-green">Перейти в корзину</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>