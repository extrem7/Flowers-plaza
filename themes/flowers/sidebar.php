<div class="sidebar col-xl-3 col-lg-4 row d-lg-block">
    <div class="col-lg-12 col-sm-6">
        <? get_template_part('views/categories') ?>
    </div>
    <? if (is_tax() && wc_get_loop_prop('total')): ?>
        <div class="col-lg-12 col-sm-6">
            <? get_template_part('views/filters') ?>
        </div>
    <? endif; ?>
</div>