<?php

defined('ABSPATH') || exit;

get_header('shop');
?>
    <div class="row flex-column-reverse flex-lg-row align-items-center align-items-lg-start mb-5">
        <? get_sidebar() ?>
        <div class="col-xl-9 col-lg-8 pt-3">
            <div class="notices-area w-100"><? wc_print_notices() ?></div>
            <?
            $globalCategories = $Flowers->woo->parentCategories();
            global $category;
            foreach ($globalCategories as $category) {
                wc_get_template_part('content', 'product_cat');
            }
            ?>
        </div>
    </div>
<? $Flowers->views()->sales() ?>
<? $Flowers->views()->advantages() ?>
<? get_footer('shop');
