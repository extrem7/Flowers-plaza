<?php

if (!defined('ABSPATH')) {
    exit;
}
global $category;

$img = categoryImage($category->term_id);
$children = get_terms('product_cat', [
    'hide_empty' => false,
    'child_of' => $category->term_id
]);

?>
<div <? wc_product_cat_class('global-category mb-3', $category); ?>>
    <h2 class="section-title mb-3"><?= $category->name ?></h2>
    <div class="category-block">
        <img src="<?= $img['url'] ?>" alt="<?= $img['alt'] ?>">
        <div class="text"><? the_field('description', $category) ?></div>
    </div>
    <? if ($children): ?>
        <div class="categories-list">
            <? foreach ($children as $child):
                $active = get_queried_object()->term_id == $child->term_id;
                ?>
                <a href="<?= get_term_link($child) ?>" class="<?= $active ? 'active' : '' ?>"><?= $child->name ?></a>
            <? endforeach; ?>
        </div>
    <? endif; ?>
</div>
