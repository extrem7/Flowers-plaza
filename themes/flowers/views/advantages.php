<div class="advantages row justify-content-center">
    <? while (have_rows('advantages', 'option')):the_row() ?>
        <div class="col-xl-4 col-lg-5 col-md-6 col-sm-9">
            <div class="item">
                <div class="icon"><img <? repeater_image('icon') ?>></div>
                <div>
                    <p class="title"><? the_sub_field('title') ?></p>
                    <p class="text"><? the_sub_field('text') ?></p>
                </div>
            </div>
        </div>
    <? endwhile; ?>
</div>