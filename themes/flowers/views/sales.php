<div class="row best-sales align-items-center">
    <div class="col-xl-9 col-lg-8 col-md-7">
        <ul class="products d-none d-md-flex">
            <?
            global $product;
            foreach (get_field('top_sales', 'option') as $post) {
                $product = wc_get_product($post);
                wc_get_template_part('content', 'product');
            }
            ?>
        </ul>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-5">
        <div class="top-carousel owl-carousel">
            <? while (have_rows('top_sales_slider', 'option')):the_row() ?>
                <div class="item">
                    <p class="title"><? the_sub_field('title') ?></p>
                    <p class="text"><? the_sub_field('text') ?></p>
                </div>
            <? endwhile; ?>
        </div>
    </div>
</div>