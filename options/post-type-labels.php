<?php

use SVKO\Lexicon\{
    I18n,
    Options,
    PostType,
    PostTypeLabels,
    WPML
};

?>
<table class="form-table">

    <tr>
        <th><label for="item_dashboard_name"><?php I18n::_e('Dashboard Menu Name') ?></label></th>
        <td>
            <input type="text" name="item_dashboard_name" id="item_dashboard_name" value="<?php echo esc_Attr(PostTypeLabels::getItemDashboardName()) ?>">
            <p class="help">
                <?php I18n::_e('This is how your post type is called in the dashboard. For example: Lexicon, Encyclopedia, Glossary, Knowledge Base, etc.') ?>
                <?php #I18n::_e('You can change this at any time later without worries.') 
                ?>
            </p>
        </td>
    </tr>
    
    <tr>
        <th><label for="item_singular_name"><?php I18n::_e('Item singular name') ?></label></th>
        <td>
            <input type="text" name="item_singular_name" id="item_singular_name" value="<?php echo esc_Attr(PostTypeLabels::getItemSingularName()) ?>">
            <p class="help"><?php I18n::_e('The singular name for an item. For example: Entry, Term, Article, etc.') ?></p>
        </td>
    </tr>

    <tr>
        <th><label for="item_plural_name"><?php I18n::_e('Item plural name') ?></label></th>
        <td>
            <input type="text" name="item_plural_name" id="item_plural_name" value="<?php echo esc_Attr(PostTypeLabels::getItemPluralName()) ?>">
            <p class="help"><?php I18n::_e('The plural name for multiple items. For example: Entries, Terms, Articles, etc.') ?></p>
        </td>
    </tr>

    

    <?php if (get_Option('permalink_structure')) : ?>
        <tr>
            <th><label><?php I18n::_e('Archive URL slug') ?></label></th>
            <td>
                <div class="input-row">
                    <div><?php echo trailingslashit(Home_Url('/')) ?></div>
                    <div class="input-element"><input type="text" name="archive_slug" id="archive_slug" value="<?php echo esc_Attr(PostTypeLabels::getArchiveSlug()) ?>"></div>
                </div>
                <p class="help"><?php I18n::_e('The url slug of your items archive. This slug must not used by another post type or page.') ?></p>
            </td>
        </tr>

        <tr>
            <th><label><?php I18n::_e('Item URL slug') ?></label></th>
            <td>
                <div class="input-row">
                    <div><?php echo trailingslashit(Home_Url('/')) ?></div>
                    <div class="input-element"><input type="text" name="item_slug" id="item_slug" value="<?php echo esc_Attr(PostTypeLabels::getItemSlug()) ?>"></div>
                    <div><?php echo User_TrailingSlashIt(sprintf(I18n::__('/%%%s-name%%'), sanitize_Title(PostTypeLabels::getItemSingularName())), 'single') ?></div>
                </div>

                <?php if (WPML::isPostTypeSlugTranslationEnabled()) : ?>
                    <p class="help warning"><?php I18n::_e('This option is not available if you translate the post type url slug with WPML.') ?></p>
                <?php else : ?>
                    <p class="help">
                        <?php I18n::_e('The url slug of your items.') ?>
                        <?php if ($taxonomies = PostType::getAssociatedTaxonomies()) : $taxonomies = Array_Map(function ($taxonomy) {
                                return "%{$taxonomy->name}%";
                            }, $taxonomies) ?>
                            <?php printf(I18n::__('You can use these placeholders: %s'), join(', ', $taxonomies)) ?>
                        <?php endif ?>
                    </p>
                <?php endif ?>
            </td>
        </tr>
    <?php endif ?>

</table>