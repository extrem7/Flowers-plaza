<? global $Flowers ?>
<header class="sidebar-header">Подбор по параметрам</header>
<form method="get" class="sidebar-block filters">
    <!--<div class="filter-block">
        <span class="title">Сортировать по:</span>
        <select name="sort">
            <option value="">Порядку</option>
            <option value="">Новизне</option>
        </select>
    </div>-->
    <div class="filter-block price-filter">
        <span class="title">Цена:</span>
        <? $Flowers->woo->priceFilter() ?>
    </div>
    <? $Flowers->woo->attributes() ?>
    <button class="btn-white">Подобрать</button>
</form>