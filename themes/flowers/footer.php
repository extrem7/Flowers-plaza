<?
global $Flowers;
if (!is_checkout()) get_template_part('views/form') ?>
</div>
</main>
<footer class="footer">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-around">
            <div class="footer-block">
                <p class="title">КОНТАКТЫ</p>
                <div class="d-flex">
                    <p><? the_field('footer_adress', 'option') ?></p>
                </div>
                <div class="d-flex mb-2">
                    <div><i class="fal fa-phone fa-rotate-90"></i></div>
                    <div>
                        <? the_field('footer_phone', 'option') ?>
                    </div>
                </div>
                <div class="d-flex">
                    <i class="fal fa-envelope"></i>
                    <? $email = get_field('email', 'option'); ?>
                    <p><a href="mailto:<?= $email ?>" class="text"><?= $email ?></a></p>
                </div>
            </div>
            <div class="footer-block">
                <p class="title">Категории</p>
                <ul>
                    <?
                    $globalCategories = $Flowers->woo->parentCategories();
                    global $category;
                    foreach ($globalCategories as $category):
                        ?>
                        <li><a href="<?= get_term_link($category) ?>">-<?= $category->name ?></a></li>
                    <? endforeach; ?>
                </ul>
            </div>
            <div class="footer-block">
                <p class="title">наши услуги</p>
                <ul>
                    <?
                    $services = $Flowers->servicesQuery();
                    while ($services->have_posts()): $services->the_post(); ?>
                        <li><a href="<? the_permalink() ?>">-<? the_title() ?></a></li>
                    <? endwhile;
                    wp_reset_query() ?>
                </ul>
            </div>
            <div class="footer-block">
                <p class="title">Мы в сети</p>
                <p><? the_field('footer_social_text', 'option') ?></p>
                <div class="social">
                    <? while (have_rows('footer_social', 'option')):the_row() ?>
                        <a href="<? the_sub_field('link') ?>" style="background-color: <? the_sub_field('color') ?>">
                            <i class="fab fa-<? the_sub_field('class') ?>"></i></a>
                    <? endwhile; ?>
                </div>
                <div class="dev">
                    <p>Создано компанией</p>
                    <a href="https://itnp.pro/" target="_blank"><img src="<?= path() ?>assets/img/author.png"
                                                                     alt=""></a>
                </div>
            </div>
        </div>
    </div>
</footer>
<div class="modal fade" id="cart-added"></div>
<? wp_footer() ?>
</body>
</html>