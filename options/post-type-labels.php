<?php
use SVKO\Lexicon\{ I18n, Options, PostType, PostTypeLabels };
?>
<table class="form-table">

    <tr>
        <th><label for="item_dashboard_name"><?php _e('Dashboard Menu Name', 'lexicon-svko') ?></label></th>
        <td>
            <input type="text" name="item_dashboard_name" id="item_dashboard_name" value="<?php echo esc_attr(PostTypeLabels::getItemDashboardName()) ?>">
            <p class="help">
                <?php _e('This is how your post type is called in the dashboard. For example: Lexicon, Encyclopedia, Glossary, Knowledge Base, etc.', 'lexicon-svko') ?>
            </p>
        </td>
    </tr>
    
    <tr>
        <th><label for="item_singular_name"><?php _e('Item singular name', 'lexicon-svko') ?></label></th>
        <td>
            <input type="text" name="item_singular_name" id="item_singular_name" value="<?php echo esc_attr(PostTypeLabels::getItemSingularName()) ?>">
            <p class="help"><?php _e('The singular name for an item. For example: Entry, Term, Article, etc.', 'lexicon-svko') ?></p>
        </td>
    </tr>

    <tr>
        <th><label for="item_plural_name"><?php _e('Item plural name', 'lexicon-svko') ?></label></th>
        <td>
            <input type="text" name="item_plural_name" id="item_plural_name" value="<?php echo esc_attr(PostTypeLabels::getItemPluralName()) ?>">
            <p class="help"><?php _e('The plural name for multiple items. For example: Entries, Terms, Articles, etc.', 'lexicon-svko') ?></p>
        </td>
    </tr>

    

    <?php if (get_option('permalink_structure')) : ?>
        <tr>
            <th><label><?php _e('Archive URL slug', 'lexicon-svko') ?></label></th>
            <td>
                <div class="input-row">
                    <div><?php echo trailingslashit(Home_Url('/')) ?></div>
                    <div class="input-element"><input type="text" name="archive_slug" id="archive_slug" value="<?php echo esc_attr(PostTypeLabels::getArchiveSlug()) ?>"></div>
                </div>
                <p class="help"><?php _e('The url slug of your items archive. This slug must not used by another post type or page.', 'lexicon-svko') ?></p>
            </td>
        </tr>

        <tr>
            <th><label><?php _e('Item URL slug', 'lexicon-svko') ?></label></th>
            <td>
                <div class="input-row">
                    <div><?php echo trailingslashit(Home_Url('/')) ?></div>
                    <div class="input-element"><input type="text" name="item_slug" id="item_slug" value="<?php echo esc_attr(PostTypeLabels::getItemSlug()) ?>"></div>
                    <div><?php echo User_TrailingSlashIt(sprintf(__('/%%%s-name%%', 'lexicon-svko'), sanitize_Title(PostTypeLabels::getItemSingularName())), 'single') ?></div>
                </div>

                <p class="help">
                    <?php _e('The url slug of your items.', 'lexicon-svko') ?>
                    <?php if ($taxonomies = PostType::getAssociatedTaxonomies()) : $taxonomies = Array_Map(function ($taxonomy) {
                            return "%{$taxonomy->name}%";
                        }, $taxonomies) ?>
                        <?php printf(__('You can use these placeholders: %s', 'lexicon-svko'), join(', ', $taxonomies)) ?>
                    <?php endif ?>
                </p>

            </td>
        </tr>
    <?php endif ?>

</table>