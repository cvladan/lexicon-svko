<?php
use SVKO\Lexicon\{ Options, PostType, PostTypeLabels, Polylang };

// Determine if Polylang is active
$is_multilingual = Polylang::isActive() && function_exists('pll_languages_list');

// Get current language
$current_language = '';
$language_name = '';
$language_flag = '';
$available_languages = [];

if ($is_multilingual) {
    // Use Polylang class method to get current language (more reliable in admin)
    $current_language = Polylang::getCurrentLanguage();
    
    // Get translations for display purposes
    $translations = Polylang::getLanguages();
    
    // Find the current language details
    if (!empty($translations) && !empty($current_language)) {
        foreach ($translations as $lang) {
            if ($lang['slug'] === $current_language) {
                $language_name = $lang['name'];
                $language_flag = $lang['flag'];
                break;
            }
        }
        
        // Build available languages array
        foreach ($translations as $lang) {
            $available_languages[$lang['slug']] = $lang['name'];
        }
    }
}
?>

<table class="form-table">
    <tr>
        <th><label for="item_dashboard_name"><?php _e('Dashboard Menu Name', 'lexicon-svko') ?></label></th>
        <td>
            <input type="text" name="item_dashboard_name" id="item_dashboard_name" value="<?php echo esc_attr(PostTypeLabels::getItemDashboardName()) ?>">
            <p class="help">
                <?php _e('This is how your post type is called in the dashboard. For example: Lexicon, Encyclopedia, Glossary, Knowledge Base, etc.', 'lexicon-svko') ?>
            </p>
            <?php if ($is_multilingual): ?>
                <p class="description">
                    <?php printf(__('Currently editing translation for: %s', 'lexicon-svko'), '<strong>' . esc_html($language_name) . '</strong>'); ?>
                </p>
            <?php endif; ?>
        </td>
    </tr>
    
    <tr>
        <th><label for="item_singular_name"><?php _e('Item singular name', 'lexicon-svko') ?></label></th>
        <td>
            <input type="text" name="item_singular_name" id="item_singular_name" value="<?php echo esc_attr(PostTypeLabels::getItemSingularName()) ?>">
            <p class="help"><?php _e('The singular name for an item. For example: Entry, Term, Article, etc.', 'lexicon-svko') ?></p>
            <?php if ($is_multilingual): ?>
                <p class="description">
                    <?php printf(__('Currently editing translation for: %s', 'lexicon-svko'), '<strong>' . esc_html($language_name) . '</strong>'); ?>
                </p>
            <?php endif; ?>
        </td>
    </tr>

    <tr>
        <th><label for="item_plural_name"><?php _e('Item plural name', 'lexicon-svko') ?></label></th>
        <td>
            <input type="text" name="item_plural_name" id="item_plural_name" value="<?php echo esc_attr(PostTypeLabels::getItemPluralName()) ?>">
            <p class="help"><?php _e('The plural name for multiple items. For example: Entries, Terms, Articles, etc.', 'lexicon-svko') ?></p>
            <?php if ($is_multilingual): ?>
                <p class="description">
                    <?php printf(__('Currently editing translation for: %s', 'lexicon-svko'), '<strong>' . esc_html($language_name) . '</strong>'); ?>
                </p>
            <?php endif; ?>
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
                <?php if ($is_multilingual): ?>
                    <p class="description">
                        <?php printf(__('Currently editing translation for: %s', 'lexicon-svko'), '<strong>' . esc_html($language_name) . '</strong>'); ?>
                    </p>
                <?php endif; ?>
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
                
                <?php if ($is_multilingual): ?>
                    <p class="description">
                        <?php printf(__('Currently editing translation for: %s', 'lexicon-svko'), '<strong>' . esc_html($language_name) . '</strong>'); ?>
                    </p>
                <?php endif; ?>
            </td>
        </tr>
    <?php endif ?>

</table>