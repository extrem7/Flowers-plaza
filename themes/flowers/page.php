<? get_header(); ?>
<div class="row flex-column-reverse flex-lg-row align-items-center align-items-lg-start mb-5">
    <? get_sidebar() ?>
    <div class="page-content col-xl-9 col-lg-8 pt-3 pb-5">
        <h1 class="section-title mb-3"><? the_title() ?></h1>
        <?= apply_filters('the_content', wpautop(get_post_field('post_content', $id), true)); ?>
    </div>
</div>
<? $Flowers->views()->sales() ?>
<? $Flowers->views()->advantages() ?>
<? get_footer() ?>
