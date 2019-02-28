<?php

defined('ABSPATH') || exit;

if (!function_exists('wc_get_gallery_image_html')) {
    return;
}

global $product;

$attachment_ids = $product->get_gallery_image_ids();
array_unshift($attachment_ids, $product->get_image_id());

$active = 'active';
foreach ($attachment_ids as $attachment_id):
    $image = wp_get_attachment_url($attachment_id);
    ?>
    <div class="thumbnail <?= $active ?>" style="background-image: url('<?= $image ?>')" data-src="<?= $image ?>"></div>
    <? $active = null; endforeach; ?>
