<? /* Template Name: Главная */
get_header(); ?>
    <div class="row mb-5">
        <div class="col-lg-6 col-md-8">
            <? $banner = get_field('banner_big') ?>
            <a href="<?= $banner['link'] ?>" class="banner" style="background-image: url('<?= $banner['image'] ?>')">
                <div><?= $banner['text'] ?></div>
                <div class="btn-white">Узнать больше</div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4 d-flex flex-column justify-content-between">
            <? while (have_rows('banners_small')):the_row() ?>
                <a href="<? the_sub_field('link') ?>" class="banner-small"
                   style="background-image: url('<? the_sub_field('image') ?>')">
                    <div class="backdrop">
                        <p class="title"><? the_sub_field('title') ?></p>
                        <p class="text"><? the_sub_field('text') ?></p>
                    </div>
                </a>
            <? endwhile; ?>
        </div>
        <ul class="col-lg-3 d-none d-lg-block">
            <?
            global $bigProduct;
            $bigProduct = 'product-card_big';
            $post = get_field('featured_product');
            $product = wc_get_product($post);
            wc_get_template_part('content', 'product');
            unset($bigProduct);
            ?>
        </ul>
    </div>
    <div class="row mb-4">
        <aside class="sidebar col-lg-3 d-none d-xl-block">
            <? get_template_part('views/categories') ?>
        </aside>
        <div class="new-products col-xl-9">
            <div class="d-flex justify-content-between align-items-center flex-column flex-lg-row">
                <h2 class="section-title mb-3 mb-lg-0 w-50 text-center text-lg-left">Новые товары</h2>
                <div class="categories-list categories-list--home justify-content-around justify-content-lg-end">
                    <?
                    $globalCategories = get_terms('product_cat', [
                        'parent' => 0,
                        'exclude' => 15
                    ]);
                    $active = 'active';
                    foreach ($globalCategories as $category): ?>
                        <a href="#" data-id="<?= $category->term_id ?>"
                           class="<?= $active ?>"><?= $category->name ?></a>
                        <? $active = null; endforeach; ?>
                </div>
            </div>
            <?
            $active = 'active';
            foreach ($globalCategories as $category): ?>
                <ul class="products products-tab <?= $active ?> pt-1" data-id="<?= $category->term_id ?>"
                    style="display: <?= $active ? 'flex' : 'none' ?>">
                    <?
                    $productQuery = $Flowers->woo->featuredProducts(4, $category);
                    while ($productQuery->have_posts()) {
                        $productQuery->the_post();
                        $product = wc_get_product($post);
                        wc_get_template_part('content', 'product');
                    } ?>
                </ul>
                <? $active = null; endforeach; ?>
        </div>
    </div>
<? $Flowers->views()->sales() ?>
<? $Flowers->views()->advantages() ?>
<?
$comment_query = new WP_Comment_Query;
$comments = $comment_query->query(['comment__in' => get_field('reviews')]);
if ($comments): ?>
    <h2 class="section-title text-center mb-5 mt-2">отзывы покупателей</h2>
    <div class="reviews-carousel owl-carousel">
        <? foreach ($comments as $comment): ?>
            <div class="review">
                <p class="text"><?= $comment->comment_content ?></p>
                <p class="name"><?= $comment->comment_author ?></p>
            </div>
        <? endforeach; ?>
    </div>
<? endif;
get_footer() ?>