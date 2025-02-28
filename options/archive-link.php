<?php

use SVKO\Lexicon\PostType;

$archive_url = get_post_type_archive_link(PostType::getPostTypeName());

if (empty($archive_url)) : ?>

    <p><?php _e('There is no link to the archive page because the archive is disabled.', 'lexicon-svko') ?></p>

<?php else : ?>

    <p>
        <?php printf(__('The archive url is: <a href="%1$s" target="_blank">%1$s</a>', 'lexicon-svko'), $archive_url) ?>
    </p>

    <p>
        <?php printf(__('The RSS feed url is: <a href="%1$s" target="_blank">%1$s</a>', 'lexicon-svko'), get_post_type_archive_feed_link(PostType::getPostTypeName())) ?>
    </p>

<?php endif;