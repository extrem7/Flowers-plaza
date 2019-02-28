<header class="sidebar-header">Категории</header>
<div class="sidebar-block">
    <ul class="categories">
        <? wp_list_categories([
            'taxonomy' => 'product_cat',
            'title_li' => '',
            'show_count' => true,
            'walker' => new Walker_Catalog()
        ]) ?>
    </ul>
</div>