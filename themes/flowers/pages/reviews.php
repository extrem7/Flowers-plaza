<? /* Template Name: Отзывы */
get_header(); ?>
    <div class="row flex-column-reverse flex-lg-row align-items-center align-items-lg-start mb-5">
        <? get_sidebar() ?>
        <div class="col-xl-9 col-lg-8 pt-3 pb-4">
            <h1 class="section-title mb-3"><? the_title() ?></h1>
            <div class="reviews-content">
                <img src="<?= path() ?>assets/img/reviews.png" alt="">
                <form class="custom-form" action="<? bloginfo('url') ?>/wp-comments-post.php" method="post">
                    <h3 class="form-title text-uppercase text-center mb-3">Добавить отзыв</h3>
                    <div class="d-flex flex-wrap justify-content-center justify-content-sm-between">
                        <input type="text" placeholder="Ваше имя*" name="author" required>
                        <input type="text" placeholder="e-mail*" name="email" required>
                        <textarea placeholder="Отзыв" name="comment" required></textarea>
                        <input type="submit" class="submit btn-green" value="Отправить">
                        <input type="hidden" name="comment_post_ID" value="<? the_ID() ?>"
                               id="comment_post_ID">
                        <input type="hidden" name="comment_parent" id="comment_parent" value="0">
                    </div>
                </form>
            </div>
            <hr>
            <?
            $comments = get_approved_comments(get_the_ID());
            if (!empty($comments)):
                foreach ($comments as $comment) :
                    ?>
                    <div class="review">
                        <p class="name"><?= $comment->comment_author ?></p>
                        <p class="text"><?= $comment->comment_content ?></p>
                        <p class="date text-right"><? comment_date('d.m.Y H:i', $comment->comment_ID) ?></p>
                    </div>
                <? endforeach; endif; ?>
        </div>
    </div>
<? $Flowers->views()->sales() ?>
<? $Flowers->views()->advantages() ?>
<? get_footer() ?>