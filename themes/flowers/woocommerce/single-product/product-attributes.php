<? global $product ?>
<ul class="props">
    <? while (have_rows('settings')):the_row() ?>
        <li>
            <div class="left"><? the_sub_field('title') ?></div>
            <div class="right"><? the_sub_field('text') ?></div>
        </li>
    <? endwhile; ?>
    <li>
        <div class="left">Наличие на складе</div>
        <div class="right <?= $product->is_in_stock() ? 'text-success' : 'text-danger' ?>"><?= $product->is_in_stock() ? 'Да' : 'Нет' ?></div>
    </li>
</ul>