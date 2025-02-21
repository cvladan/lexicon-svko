<?php

use SVKO\Lexicon\{
    I18n,
    PostTypeLabels
};

?>
<table class="form-table">
    <tr>
        <th><label for="post_type_slug"><?php I18n::_e('Post Type Slug') ?></label></th>
        <td>
            <input type="text" name="post_type_slug" id="post_type_slug" value="<?= esc_attr(PostTypeLabels::getPostTypeSlug()) ?>">
            <p class="help"><?php I18n::_e('The internal name used to register this post type. This should be a unique, lowercase alphanumeric name (hyphens allowed).') ?></p>
        </td>
    </tr>
</table>