<?php

use SVKO\Lexicon\PostTypeLabels;

?>
<table class="form-table">
    <tr>
        <th><label for="post_type_slug"><?php _e('Post Type Slug', 'lexicon-svko') ?></label></th>
        <td>
            <input type="text" name="post_type_slug" id="post_type_slug" value="<?= esc_attr(PostTypeLabels::getPostTypeSlug()) ?>">
            <p class="help"><?php _e('The internal name used to register this post type. This should be a unique, lowercase alphanumeric name (hyphens allowed).', 'lexicon-svko') ?></p>
        </td>
    </tr>
</table>