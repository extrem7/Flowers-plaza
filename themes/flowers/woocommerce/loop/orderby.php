<?php

if (!defined('ABSPATH')) {
    exit;
}
global $Flowers;
?>
<div class="sort-line">
    <p class="pagination-count"><?= $Flowers->woo->paginationText() ?></p>
    <form method="post">
        <label>
            Показать:
            <select class="limit" name="perpage">
                <?
                $limits = [12, 16, 20];
                foreach ($limits as $limit): ?>
                    <option <? selected($_POST['perpage'] ? $_POST['perpage'] : $_COOKIE['perpage'], $limit); ?>
                            value="<?= $limit ?>"><?= $limit ?></option>
                <? endforeach; ?>
            </select>
            <input type="hidden" name="paged" value="1"/>
        </label>
    </form>
    <form method="get">
        <label>
            Фильтр :
            <select name="orderby" class="orderby">
                <? foreach ($catalog_orderby_options as $id => $name) : ?>
                    <option value="<?= esc_attr($id); ?>" <? selected($orderby, $id); ?>><?= esc_html($name); ?></option>
                <? endforeach; ?>
            </select>
        </label>
        <input type="hidden" name="paged" value="1"/>
    </form>
    <? wc_query_string_form_fields(null, array('orderby', 'submit', 'paged', 'product-page')); ?>
</div>
