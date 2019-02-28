<? /* Template Name: Контакты */
get_header(); ?>
    <div class="row flex-column-reverse flex-lg-row align-items-center align-items-lg-start mb-5">
        <? get_sidebar() ?>
        <div class="col-xl-9 col-lg-8 pt-3 pb-4">
            <h1 class="section-title mb-3">Наши контакты</h1>
            <div class="contacts-content">
                <img src="<?= path() ?>assets/img/contacts.png" alt="">
                <div class="text">
                    <?= apply_filters('the_content', wpautop(get_post_field('post_content', $id), true)); ?>
                </div>
            </div>
            <h3 class="contacts-title mt-4 mb-4">НАШЕ МЕСТОНАХОЖДЕНИЕ</h3>
            <div class="map"><? the_field('map') ?></div>
            <h3 class="contacts-title mt-3 mb-3">Время работы:</h3>
            <p class="time"><? the_field('time') ?></p>
            <div class="custom-form text-center contacts-form mt-4">
                    <?= do_shortcode('[contact-form-7 id="125" title="Контакты"]') ?>
            </div>
        </div>
    </div>
<? $Flowers->views()->sales() ?>
<? $Flowers->views()->advantages() ?>
<? get_footer() ?>