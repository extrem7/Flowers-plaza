<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= wp_get_document_title() ?></title>
    <? wp_head() ?>
</head>
<body <? body_class() ?>>
<header class="header">
    <div class="header-top">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between flex-column flex-md-row">
                <a href="<? bloginfo('url') ?>" class="logo">
                    <img src="<? the_field('logo', 'option') ?>" alt="logo" class="img-fluid">
                </a>
                <div class="d-flex flex-column flex-sm-row">
                    <div class="info">
                        <img src="<?= path() ?>assets/img/icon-map.png" alt="">
                        <div>
                            <p class="title"><? the_field('header_adress', 'option') ?></p>
                            <p class="text"><? the_field('header_street', 'option') ?></p>
                        </div>
                    </div>
                    <div class="info">
                        <img src="<?= path() ?>assets/img/icon-phone.png" alt="">
                        <div>
                            <? $phone = get_field('header_phone', 'option');
                            $email = get_field('email', 'option');
                            ?>
                            <a href="<?= phoneLink($phone) ?>" class="title"><?= $phone ?></a>
                            <a href="mailto:<?= $email ?>" class="text"><?= $email ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-bottom">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center w-100">
                <nav class="navbar navbar-expand-md">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#header"
                            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                        <span class="navbar-toggler-icon"></span>
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <? wp_nav_menu([
                        'menu' => 'Хедер',
                        'theme_location' => 'header',
                        'container' => 'div',
                        'container_id' => 'header',
                        'container_class' => 'collapse navbar-collapse',
                        'menu_id' => false,
                        'menu_class' => 'navbar-nav align-items-center flex-column flex-md-row',
                        'depth' => 2,
                        'fallback_cb' => 'bs4navwalker::fallback',
                        'walker' => new WP_Bootstrap_Navwalker()
                    ]);
                    ?>
                </nav>
                <? woocommerce_mini_cart() ?>
            </div>
        </div>
    </div>
</header>
<? woocommerce_breadcrumb() ?>
<main class="main">
    <div class="container">
