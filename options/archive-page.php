<?php

use SVKO\Lexicon\{ Options, PostTypeLabels };

?>
<table class="form-table">

    <tr>
        <th><label for="enable_archive"><?php _e('Enable Archive', 'lexicon-svko') ?></label></th>
        <td>
            <select name="enable_archive" id="enable_archive">
                <option value="1" <?php selected(Options::get('enable_archive')) ?>><?php _e('On', 'lexicon-svko') ?></option>
                <option value="0" <?php selected(!Options::get('enable_archive')) ?>><?php _e('Off', 'lexicon-svko') ?></option>
            </select>
            <p class="help"><?php _e('Enables or disables the global archive. This does not affect the taxonomy archives.', 'lexicon-svko') ?></p>
        </td>
    </tr>

    <tr>
        <th><label><?php printf(__('%s per page', 'lexicon-svko'), PostTypeLabels::getItemPluralName()) ?></label></th>
        <td>
            <input type="number" name="posts_per_page" value="<?php echo get_Option('posts_per_page') ?>" min="1" max="<?php echo PHP_INT_MAX ?>" step="1">
            <p class="help">
                <?php printf(__('This option affects all %s archive pages.', 'lexicon-svko'), PostTypeLabels::getItemDashboardName()) ?>
                <?php printf(__('You can use "-1" to disable the pagination and show all %s in one page.', 'lexicon-svko'), PostTypeLabels::getItemPluralName()) ?>
            </p>
        </td>
    </tr>

    <tr>
        <th><label for="prefix_filter_for_archives"><?php _e('Prefix filter', 'lexicon-svko') ?></label></th>
        <td>
            <select name="prefix_filter_for_archives" id="prefix_filter_for_archives">
                <option value="1" <?php selected(Options::get('prefix_filter_for_archives')) ?>><?php _e('On', 'lexicon-svko') ?></option>
                <option value="0" <?php selected(!Options::get('prefix_filter_for_archives')) ?>><?php _e('Off', 'lexicon-svko') ?></option>
            </select>
            <p class="help"><?php _e('Enables or disables the prefix filter above the first item in the archive.', 'lexicon-svko') ?></p>
        </td>
    </tr>

    <tr>
        <th><label for="prefix_filter_archive_depth"><?php _e('Prefix filter depth', 'lexicon-svko') ?></label></th>
        <td>
            <input type="number" name="prefix_filter_archive_depth" id="prefix_filter_archive_depth" value="<?php echo Options::get('prefix_filter_archive_depth') ?>" min="1" max="<?php echo PHP_INT_MAX ?>" step="1">
            <p class="help"><?php _e('The depth of the prefix filter is usually the number of rows with prefixes which are shown.', 'lexicon-svko') ?></p>
        </td>
    </tr>

</table>