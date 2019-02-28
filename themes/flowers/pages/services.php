<? /* Template Name: Услуги */
get_header(); ?>
    <div class="row flex-column-reverse flex-lg-row align-items-center align-items-lg-start mb-5">
        <? get_sidebar() ?>
        <div class="col-xl-9 col-lg-8 pt-3">
            <h2 class="section-title mb-3"><? the_title() ?></h2>
            <?
            $services = $Flowers->servicesQuery();
            while ($services->have_posts()): $services->the_post();
                ?>
                <div class="service-item">
                    <a href="<? the_permalink() ?>">
                        <? the_post_thumbnail() ?>
                    </a>
                    <div class="content">
                        <div>
                            <h3 class="title"><? the_title() ?></h3>
                            <div class="excerpt"><? the_excerpt() ?></div>
                            <b><? the_field('additional') ?></b>
                        </div>
                        <a href="<? the_permalink() ?>" class="btn-green">Подробная информация</a>
                    </div>
                </div>
                <hr>
            <? endwhile; ?>
        </div>
    </div>
<? $Flowers->views()->sales() ?>
<? $Flowers->views()->advantages() ?>
<? get_footer() ?>