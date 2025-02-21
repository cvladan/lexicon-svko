<?php

namespace SVKO\Lexicon;

abstract class Options
{
    private static $arr_option_box = [], $page_slug, $options_key;

    public static function init(): void
    {
        self::$page_slug = PostType::getPostTypeName() . '-options';
        self::$options_key = 'wp_plugin_' . PostType::getPostTypeName();
        
        # Option boxes
        static::$arr_option_box = [
            'main' => [],
            'side' => []
        ];

        add_action('admin_menu', [static::class, 'addOptionsPage']);
    }

    public static function addOptionsPage(): void
    {
        $handle = add_options_page(
            sprintf(I18n::__('%s Settings'), PostTypeLabels::getItemDashboardName()),
            PostTypeLabels::getItemDashboardName(),
            'manage_options',
            static::$page_slug,
            [static::class, 'printOptionsPage']
        );

        # Add options page link to post type sub menu
        add_submenu_page('edit.php?post_type=' . PostType::getPostTypeName(), null, I18n::__('Settings'), 'manage_options', 'options-general.php?page=' . static::$page_slug);

        # Add JavaScript to this handle
        add_action('load-' . $handle, [static::class, 'loadOptionsPage']);

        # Add option boxes
        static::addOptionBox(I18n::__('Labels'), Core::$plugin_folder . '/options/post-type-labels.php');
        static::addOptionBox(I18n::__('Appearance'), Core::$plugin_folder . '/options/appearance.php');
        static::addOptionBox(I18n::__('Features'), Core::$plugin_folder . '/options/features.php');
        static::addOptionBox(I18n::__('Taxonomies'), Core::$plugin_folder . '/options/taxonomies.php');
        static::addOptionBox(I18n::__('Archives'), Core::$plugin_folder . '/options/archive-page.php');
        static::addOptionBox(I18n::__('Search'), Core::$plugin_folder . '/options/search.php');
        static::addOptionBox(I18n::__('Single View'), Core::$plugin_folder . '/options/single-page.php');
        static::addOptionBox(I18n::__('Related Items'), Core::$plugin_folder . '/options/related-items.php');
        static::addOptionBox(I18n::__('Cross Linking'), Core::$plugin_folder . '/options/cross-linking.php');
        static::addOptionBox(I18n::__('Tooltips'), Core::$plugin_folder . '/options/tooltips.php');
        static::addOptionBox(I18n::__('Archive URLs'), Core::$plugin_folder . '/options/archive-link.php', 'side');
    }

    public static function getOptionsPageUrl(array $args = []): string
    {
        $url = add_query_arg(['page' => static::$page_slug], Admin_Url('options-general.php'));

        if (!empty($args))
            $url = add_query_arg($args, $url);

        return $url;
    }

    public static function addOptionBox(string $title, string $include_file, string $column = 'main'): void
    {
        # Title cannot be empty
        if (empty($title)) $title = '&nbsp;';

        # Column (can be 'side' or 'main')
        if ($column != 'main') $column = 'side';

        # Add a new box
        if (is_File($include_file)) {
            static::$arr_option_box[$column][] = (object) [
                'title' => $title,
                'file' => $include_file,
                'slug' => PathInfo($include_file, PATHINFO_FILENAME)
            ];
        }
    }

    public static function loadOptionsPage(): void
    {
        flush_rewrite_rules();

        # If this is a Post request to save the options
        if (static::saveOptions()) {
            wp_redirect(static::getOptionsPageUrl(['options_saved' => 'true']));
        }

        wp_enqueue_style('dashboard');

        wp_enqueue_style(static::$page_slug, Core::$base_url . '/options/options.css');

        # Remove incompatible JS Libs
        wp_dequeue_script('post');
    }

    public static function printOptionsPage(): void
    {
        include Core::$plugin_folder . '/options/options.php';
    }

    public static function saveOptions(): bool
    {
        # Check if this is a post request
        if (empty($_POST)) return false;

        # Check the nonce
        check_admin_referer('save_' . PostType::getPostTypeName() . '_options');

        # Clean the Post array
        $options = stripSlashes_Deep($_POST);
        unset($options['_wpnonce'], $options['_wp_http_referer']);
        $options = Array_Filter($options, function ($value) {
            return $value == '0' || !empty($value);
        });

        # Save Options
        return (bool) update_option(static::$options_key, $options);
    }

    public static function getDefaultOptions(): array
    {
        return [
            'item_dashboard_name' => I18n::__('Lexicon'),
            'item_singular_name' => I18n::__('Entry'),
            'item_plural_name' => I18n::__('Entries'),
            'item_slug' => I18n::__('lexicon'),
            'archive_slug' => I18n::__('lexicon'),
            'embed_default_style' => true,
            'enable_editor' => true,
            'enable_block_editor' => false,
            'enable_excerpt' => true,
            'taxonomies' => [PostType::getPostTypeName() . '-tag'],
            'enable_archive' => true,
            'items_per_page' => 100,
            'prefix_filter_for_archives' => true,
            'prefix_filter_archive_depth' => 3,
            'prefix_filter_for_singulars' => true,
            'prefix_filter_singular_depth' => 3,
            'relation_taxonomy' => PostType::getPostTypeName() . '-tag',
            'min_relation_threshold' => 1,
            'cross_link_title_length' => apply_filters('excerpt_length', 55),
            'cross_linker_priority' => 'after_shortcodes',
            'activate_tooltips' => true,
        ];
    }

    public static function get(string $key = '', $default = false)
    {
        static $arr_options;

        if (empty($arr_options)) {
            $saved_options = (array) get_Option(static::$options_key);
            $default_options = static::getDefaultOptions();
            $arr_options = Array_Merge($default_options, $saved_options);
        }

        # Locate the option
        if (empty($key))
            return $arr_options;
        elseif (isset($arr_options[$key]))
            return $arr_options[$key];
        else
            return $default;
    }
}

